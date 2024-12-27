<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Mail\UserCreatedMail;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\UploadImageService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Repositories\RoleRepostiory;

class UserController extends Controller
{
    protected $uploadImageService;

    /**
     * UserController constructor.
     * @param UploadImageService $service
     */
    public function __construct(UploadImageService $uploadImageService, private RoleRepostiory $roleRepository)
    {
        $this->uploadImageService = $uploadImageService;
    }


    public function getAllUsers()
    {
        return User::where("isDeleted", "=", 0)->get();
    }

    public function getUserById($id){
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    public function getUser(Request $request)
    {
        return User::find($request->user()->id);
    }

    public function createUser(CreateUserRequest $request)
    {
        $name = "";
        if ($request->hasFile('file')) {
            $name = $this->uploadImageService->uploadImage($request);
        }
        $user = new User($request->all());
        $user->password = Hash::make($request->password);
        $user->image = $name;
        $user->save();
        $responseBody['user']= $user;
        $responseBody['message']= 'User created successfully!';
        if($request->roleIdsList){
            $this->roleRepository->assignRoles($request->roleIdsList, $user->id);
        }
        
        Mail::to($user->email)->queue(new UserCreatedMail($user->fresh(), $request->password));
        return response()->json($responseBody, 201);
    }

    public function updateUserInfo(UpdateUserRequest $request)
    {
        $user_id = $request->id;
        $user = User::findOrFail($user_id);
        if ($request->hasFile('file')) {
            $name = $this->uploadImageService->uploadImage($request);
            $image = ['image' => $name];
        } else {
            $image = [];
        }
        
        $userData = array_merge($request->all(), $image);
        
        if ($request->filled('password') && $request->filled('password_confirm')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        if($request->roleIdsList){
            $this->roleRepository->assignRoles($request->roleIdsList, $user_id);
            $this->roleRepository->removeRoles($request->roleIdsList, $user_id);
        }
        
        return response()->json($user->fresh());
    }

    public function deleteUser($id){
        $user = User::findOrFail($id);
        $user->isDeleted = 1;
        $user->save();

        $responseBody['message']= "Utilisateur supprimé!";
        $responseBody['user']= $user;

        return response()->json($responseBody);
    }

    function givePermission(Request $request, string $user_id)
    {
        $user = User::findOrFail($user_id);
        if ($user->hasPermissionTo($request->permission)) {
            return response(['message' => 'Permission exists']);
        }
        $user->givePermissionTo($request->permission);
        return response(['message' => 'Permission added to user']);
    }
    function revokePermission(string $user_id, string $permission_id)
    {
        $user = User::findOrFail($user_id);
        $permission = Permission::findOrFail($permission_id);
        if ($user->hasPermissionTo($permission)) {
            $user->revokePermissionTo($permission);
            return response(['message' => 'Permission revoked from user']);
        }
        return response(['message' => 'Permission does not exist']);
    }

    public function getAllUsersByColumn(Request $request){
        $users = User::get(['id',$request->column]);
        return $users;
    }

    public function getAllUsersByParams(Request $request)
    {
        $query = User::query();
        $query->where('isDeleted', 0);
        $query->orderBy($request->sort, $request->ascSort);
        $query->with('roles');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('immat', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->immat) {
            $query->where('immat', $request->immat);
        }
        if ($request->name) {
            $query->where('name', $request->name);
        }
        if ($request->email) {
            $query->where('email', $request->email);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $users = $query->paginate((int)$request->perPage ?? 5);
        
        return response()->json($users);
    }   
}
