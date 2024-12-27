<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepostiory;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(private RoleRepostiory $roleRepository)
    {
    }
    
    public function getAllRoles()
    {
        $roles = $this->roleRepository->getAllRoles();

        $responseBody['roles'] = $roles;
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }
}
