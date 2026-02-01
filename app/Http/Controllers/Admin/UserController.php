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
        $query = User::with('role')->orderBy('id', 'desc');

        // If not Super Admin, hide Super Admins
        if (auth()->user()->role->slug !== 'super-admin') {
            $query->whereHas('role', function ($q) {
                $q->where('slug', '!=', 'super-admin');
            });
            
            // Optionally: If using branch logic, filter by branch here
             if (auth()->user()->branch_id) {
                // Show users in same branch OR users with no branch (if applicable, though usually strict)
                $query->where(function($q) {
                     $q->where('branch_id', auth()->user()->branch_id)
                       ->orWhereNull('branch_id'); // Allow seeing globals if needed, or remove this line for strict isolation
                });
            }
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $branches = \App\Models\Branch::all();

        return view('admin.users.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'branch_id' => $request->branch_id,
        ]);

        $role = Role::find($request->role_id);
        
        if ($role->slug === 'patient') {
             \App\Models\Patient::create([
                'user_id' => $user->id,
                'patient_code' => 'PT-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }
        
        if ($role->slug === 'doctor') {
             \App\Models\DoctorProfile::create([
                'user_id' => $user->id,
                'specialization' => 'General', // Default
                'qualification' => 'MBBS',
                'experience_years' => 0
            ]);
        }

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
