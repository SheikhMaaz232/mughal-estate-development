<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'name_en' => 'required|unique:permissions,name_en',
            'name_ur' => 'required|unique:permissions,name_ur',
        ]);

        Permission::create(['name' => $request->name,'name_en' => $request->name_en,'name_ur' => $request->name_ur]);

        return redirect()->route('permissions.index')->with('success', __('messages.permission-created'));
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'name_en' => 'required|unique:permissions,name_en,' . $permission->id,
            'name_ur' => 'required|unique:permissions,name_ur,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name, 'name_en' => $request->name_en, 'name_ur' => $request->name_ur]);

        return redirect()->route('permissions.index')->with('success', __('messages.permission-updated'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', __('messages.permission-deleted'));
    }
}
