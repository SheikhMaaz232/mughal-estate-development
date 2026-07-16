<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10); // use pagination
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array'
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users-roles.index')->with('success', __('messages.role-updated'));
    }

    public function destroy(User $user)
    {
        $user->syncRoles([]); // remove all roles
        return redirect()->route('users-roles.index')->with('success', __('messages.role-deleted'));
    }
}
