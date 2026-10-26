<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AdminController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.hak-akses', compact('roles', 'permissions')); // Ensure you have a view file at resources/views/admin/index.blade.php
    }

    public function getPermissionsByRole($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions()->pluck('name');

        return response()->json([
            'permission_names' => $permissions,
        ]);
    }

    public function updatePermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($request->role_id);

        // Sync permissions
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', 'Hak akses berhasil diperbarui.');
    }
}
