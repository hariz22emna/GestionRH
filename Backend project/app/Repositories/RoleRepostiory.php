<?php
namespace App\Repositories;

use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleRepostiory
{
    public function getAllRoles()
    {
        $roles = Role::all();
        return $roles;
    }

    function assignRoles($request, $userId)
    {
        $roleIds = json_decode($request, true);
        $roles = Role::whereIn('id', $roleIds)->get();
        if (!empty($roles)) {
            $user = User::findOrFail($userId);
            foreach ($roles as $role) {
                if (!$user->hasRole($role)) {
                    $user->assignRole($role);
                }
            }
        }

        return $roles;
    }

    function removeRoles($request, $userId)
    {
        $user = User::with('roles')->findOrFail($userId);

        if ($user) {
            $currentRoleIds = $user->roles->pluck('id');
            $roleIds = json_decode($request, true);
            $rolesToRemove = $currentRoleIds->diff($roleIds);

            if (!$rolesToRemove->isEmpty()) {
                $roles = $user->roles()->whereIn('id', $rolesToRemove)->get();

                foreach ($roles as $role) {
                    if ($user->hasRole($role)) {
                        $user->removeRole($role);
                    }
                }
            }
        }
    }
}