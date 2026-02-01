<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards = Ward::withCount('beds')->withCount(['beds as available_beds_count' => function ($query) {
            $query->where('is_available', true);
        }])->get();
        return view('admin.ipd.wards.index', compact('wards'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'type' => 'required', 'floor_number' => 'nullable']);
        Ward::create($request->all());
        return back()->with('success', 'Ward added successfully.');
    }

    public function destroy(Ward $ward)
    {
        $ward->delete();
        return back()->with('success', 'Ward deleted.');
    }
}
