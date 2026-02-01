<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Ward;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index()
    {
        $beds = Bed::with('ward')->latest()->paginate(20);
        $wards = Ward::all();
        return view('admin.ipd.beds.index', compact('beds', 'wards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'bed_number' => 'required',
            'daily_charge' => 'required|numeric|min:0',
        ]);

        Bed::create($request->all());
        return back()->with('success', 'Bed created successfully.');
    }

    public function edit(Bed $bed)
    {
        $wards = Ward::all();
        return view('admin.ipd.beds.edit', compact('bed', 'wards'));
    }

    public function update(Request $request, Bed $bed)
    {
        $bed->update($request->all());
        return redirect()->route('admin.ipd.beds.index')->with('success', 'Bed updated.');
    }

    public function destroy(Bed $bed)
    {
        $bed->delete();
        return back()->with('success', 'Bed deleted.');
    }
}
