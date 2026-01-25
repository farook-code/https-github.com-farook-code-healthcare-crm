<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

public function edit(User $user)
{
    $roles = Role::all();
    $departments = Department::where('status', 1)->get();

    return view('admin.users.edit', compact('user', 'roles', 'departments'));
}

   public function update(Request $request, User $user)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email,' . $user->id,
        'role_id'       => 'required|exists:roles,id',
        'department_id' => 'nullable|exists:departments,id',
    ]);

    // If role is not doctor/nurse → remove department
    $newRole = Role::find($request->role_id);
    $roleSlug = $newRole ? $newRole->slug : '';

    $departmentId = in_array($roleSlug, ['doctor', 'nurse'])
        ? $request->department_id
        : null;

    $user->update([
        'name'          => $request->name,
        'email'         => $request->email,
        'role_id'       => $request->role_id,
        'department_id' => $departmentId,
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User updated successfully');
}

public function toggleStatus(User $user)
{
    $user->status = ! $user->status;
    $user->save();

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User status updated');
}


}
