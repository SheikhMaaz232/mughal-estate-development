<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'name_en' => 'required|unique:roles,name_en',
            'name_ur' => 'required|unique:roles,name_ur',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name, 'name_en' => $request->name_en,'name_ur' => $request->name_ur]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', __('messages.role-created'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'name_en' => 'required|unique:roles,name_en,' . $role->id,
            'name_ur' => 'required|unique:roles,name_ur,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name, 'name_en' => $request->name_en,'name_ur' => $request->name_ur]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', __('messages.role-updated'));
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', __('messages.role-deleted'));
    }
}
