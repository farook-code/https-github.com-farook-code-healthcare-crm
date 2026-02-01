<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationTheater;
use Illuminate\Http\Request;

class OperationTheaterController extends Controller
{
    public function index()
    {
        $theaters = OperationTheater::all();
        return view('admin.ot.theaters.index', compact('theaters'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'hourly_charge' => 'required|numeric']);
        OperationTheater::create($request->all());
        return back()->with('success', 'OT Room added.');
    }

    public function update(Request $request, OperationTheater $theater)
    {
        $theater->update($request->all());
        return back()->with('success', 'OT updated.'); // Simplified for speed
    }

    public function destroy(OperationTheater $theater)
    {
        $theater->delete();
        return back()->with('success', 'OT deleted.');
    }
}
