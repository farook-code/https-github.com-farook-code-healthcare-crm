<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineInteraction;

class DrugInteractionService
{
    /**
     * Check if the new medicine interacts with any existing medicines in the list.
     * 
     * @param string $newMedicineName
     * @param array $existingMedicineNames List of medicine names
     * @return array List of warnings
     */
    public function checkInteraction($newMedicineName, $existingMedicineNames)
    {
        $warnings = [];

        // 1. Get ID of new medicine
        $newMed = Medicine::where('name', $newMedicineName)->first();
        if (!$newMed) return [];

        // 2. Get IDs of existing medicines
        $existingMeds = Medicine::whereIn('name', $existingMedicineNames)->get();

        if ($existingMeds->isEmpty()) return [];

        foreach ($existingMeds as $existingMed) {
             // Check in DB (Bidirectional)
             $interaction = MedicineInteraction::where(function($q) use ($newMed, $existingMed) {
                 $q->where('medicine_id', $newMed->id)
                   ->where('interacting_medicine_id', $existingMed->id);
             })->orWhere(function($q) use ($newMed, $existingMed) {
                 $q->where('medicine_id', $existingMed->id)
                   ->where('interacting_medicine_id', $newMed->id);
             })->first();

             if ($interaction) {
                 $warnings[] = [
                    'drug_a' => $existingMed->name,
                    'drug_b' => $newMed->name,
                    'severity' => ucfirst($interaction->severity) . ': ' . $interaction->description
                 ];
             }
        }

        return $warnings;
    }
}

