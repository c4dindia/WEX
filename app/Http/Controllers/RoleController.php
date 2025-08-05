<?php

namespace App\Http\Controllers;

use App\Models\AssignModule;
use App\Models\AssignRole;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function add_role(Request $request)
    {
        $roles = Role::create([
            'name' => $request->name,
            'status' => $request->status
        ]);
        if ($roles) {
            return back()->withSuccess('Role added successfully!');
        }
    }

    public function edit_role($id)
    {
        $role = Role::find($id);
        return view('admin.edit-role')->with('role', $role);
    }

    public function update_role(Request $request)
    {
        $id = $request->input('id');
        $assignedRole = AssignRole::where('role_id', $id)->exists();

        if ($assignedRole) {
            return redirect()->to('role')->withError('This role already assigned to user and cannot be updated!');
        }

        if ($id) {
            $role = ([
                'name' => $request->name,
                'status' => $request->status
            ]);
            Role::where('id', $id)->update($role);
            return redirect()->to('role')->withSuccess('Role updated successfully!');
        }
    }

    public function delete_role($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return back()->withSuccess('Role delete successfully!');
        }
    }

    public function check_assigned_module(Request $request)
    {
        $roleId = $request->input('role_id');
        $assignedModules = AssignModule::where('role_id', $roleId)->pluck('module_id')->toArray();

        return response()->json($assignedModules);
    }

    public function save_assign_module(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'module' => 'required|array',
        ], [
            'role.required' => 'The role field is required.'
        ]);
        $roleId = $request->role;
        $moduleIds = $request->module;

        AssignModule::where('role_id', $roleId)->delete();

        foreach ($moduleIds as $moduleId) {
            AssignModule::create([
                'role_id' => $roleId,
                'module_id' => $moduleId
            ]);
        }
        return back()->with('success', 'Modules assigned to role successfully!');
    }
}
