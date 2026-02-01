<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceProvider;
use Illuminate\Http\Request;

class InsuranceProviderController extends Controller
{
    public function index()
    {
        $providers = InsuranceProvider::withCount('patientInsurances')->get();
        return view('admin.insurance.providers.index', compact('providers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        InsuranceProvider::create($request->all());
        return back()->with('success', 'Provider added.');
    }

    public function destroy(InsuranceProvider $provider)
    {
        $provider->delete();
        return back()->with('success', 'Provider deleted.');
    }
}
