<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleAssignmentService
{
    public function getAllUsers()
    {
        return User::with('roles')->get();
    }

    public function getAllRoles()
    {
        return Role::all();
    }

    public function assignRoleToUser(User $user, string $role)
    {
        $user->syncRoles([$role]);
    }
}
