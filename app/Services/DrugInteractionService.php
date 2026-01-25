<?php

namespace App\Services;

class DrugInteractionService
{
    protected $interactions = [
        // BAD PAIRS: [DrugA, DrugB] => Severity
        'Aspirin' => [
            'Warfarin' => 'Severe: Increased risk of bleeding.',
            'Ibuprofen' => 'Moderate: Reduces antiplatelet effect of Aspirin.',
            'Heparin' => 'Severe: Increased bleeding risk.',
            'Naproxen' => 'Moderate: Gastrointestinal toxicity.'
        ],
        'Warfarin' => [
            'Aspirin' => 'Severe: Increased risk of bleeding.',
            'Vitamin K' => 'Severe: Reduces efficacy of Warfarin.',
            'Ciprofloxacin' => 'Moderate: May increase Warfarin levels.',
            'Simvastatin' => 'Moderate: Increased bleeding risk.'
        ],
        'Amoxicillin' => [
            'Methotrexate' => 'Moderate: Increased toxicity of Methotrexate.',
            'Allopurinol' => 'minor: Increased risk of rash.'
        ],
        'Sildenafil' => [
            'Nitroglycerin' => 'Critical: Severe hypotension (fatal drop in blood pressure).',
            'Isosorbide Mononitrate' => 'Critical: Severe hypotension.'
        ],
        'Simvastatin' => [
            'Amlodipine' => 'Moderate: Increased risk of myopathy at high doses.',
            'Gemfibrozil' => 'Severe: High risk of muscle damage (rhabdomyolysis).'
        ]
    ];

    /**
     * Check if the new medicine interacts with any existing medicines in the list.
     * 
     * @param string $newMedicine
     * @param array $existingMedicines List of medicine names
     * @return array List of warnings
     */
    public function checkInteraction($newMedicine, $existingMedicines)
    {
        $warnings = [];

        foreach ($existingMedicines as $existing) {
            // Case insensitive match logic could be added here
            // Check New vs Existing
            if (isset($this->interactions[$newMedicine][$existing])) {
                $warnings[] = [
                    'drug_a' => $newMedicine,
                    'drug_b' => $existing,
                    'severity' => $this->interactions[$newMedicine][$existing]
                ];
            }
            
            // Check Existing vs New (Reverse)
            elseif (isset($this->interactions[$existing][$newMedicine])) {
                 $warnings[] = [
                    'drug_a' => $existing,
                    'drug_b' => $newMedicine,
                    'severity' => $this->interactions[$existing][$newMedicine]
                ];
            }
        }

        return $warnings;
    }
}
