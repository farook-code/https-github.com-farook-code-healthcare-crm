<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;
class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('id', 'desc')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
            'status' => 1,
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created');
    }

    public function toggleStatus(Department $department)
    {
        $department->status = ! $department->status;
        $department->save();

        return redirect()->back();
    }

    public function users()
{
    return $this->hasMany(User::class);
}
}
