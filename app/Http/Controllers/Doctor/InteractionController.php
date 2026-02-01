<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicineInteraction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function check(Request $request)
    {
        $names = $request->input('medicines', []);

        if (empty($names)) {
            return response()->json(['interactions' => []]);
        }

        // Get IDs from names
        $medicines = \App\Models\Medicine::whereIn('name', $names)->get();
        $ids = $medicines->pluck('id')->toArray();

        if (count($ids) < 2) {
             return response()->json(['interactions' => []]);
        }

        // Find interactions where both the source and target are in the selected list
        $interactions = MedicineInteraction::whereIn('medicine_id', $ids)
            ->whereIn('interacting_medicine_id', $ids)
            ->with(['medicine', 'interactingMedicine'])
            ->get();

        return response()->json([
            'status' => 'success',
            'interactions' => $interactions
        ]);
    }
}
