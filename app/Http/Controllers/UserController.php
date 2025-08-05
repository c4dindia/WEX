<?php

namespace App\Http\Controllers;

use App\Models\AssignRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function user()
    {
        $users = User::leftJoin('assign_roles', 'assign_roles.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'assign_roles.role_id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', DB::raw('GROUP_CONCAT(roles.name SEPARATOR ", ") as role_name'))
            ->where('users.is_admin', 2)
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->get();
        $roles = Role::all();

        return view('admin.user')->with('users', $users)->with('roles', $roles);
    }

    public function add_user(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 2,
            'status' => 1,
        ]);
        if($user){
            return back()->withSuccess('User added successfully!');
        }
    }

    public function edit_user($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $user_role = AssignRole::join('roles', 'roles.id', '=', 'assign_roles.role_id')
            ->where('user_id', $id)
            ->pluck('roles.name');

        return view('admin.edit-user')->with('user', $user)->with('roles', $roles)->with('user_role', $user_role);
    }

    public function update_user(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $user = ([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
            User::where('id', $id)->update($user);

            AssignRole::where('user_id', $id)->delete();
            foreach ($request->role as $roleId) {
                AssignRole::create([
                    'user_id' => $id,
                    'role_id' => $roleId
                ]);
            }
            return redirect()->to('user')->withSuccess('User updated successfully!');
        }
    }

    public function delete_user($id){
        $user = User::find($id);
        if($user){
            $user->delete();
            return back()->withSuccess('User deleted successfully!');
        }
    }

    public function check_assigned_role(Request $request)
    {
        $userId = $request->input('user_id');
        $assignedroles = AssignRole::where('user_id', $userId)->pluck('role_id')->toArray();

        return response()->json($assignedroles);
    }

    public function save_assign_role(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'role' => 'required|array',
        ], [
            'user.required' => 'The user field is required.'
        ]);
        $userId = $request->user;
        $roleIds = $request->role;

        AssignRole::where('user_id', $userId)->delete();

        foreach ($roleIds as $roleId) {
            AssignRole::create([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);
        }
        return back()->with('success', 'Roles assigned to user successfully!');
    }
}


