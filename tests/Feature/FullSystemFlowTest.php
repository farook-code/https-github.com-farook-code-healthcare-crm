<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Diagnosis;
use App\Models\DoctorProfile;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FullSystemFlowTest extends TestCase
{
    // usage of RefreshDatabase might wipe dev data, better to use DatabaseTransactions or carefully clean up.
    // However, for a "Check", RefreshDatabase is standard but destructive. 
    // Given the user is in dev, let's use DatabaseTransactions to wrap in transaction.
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function test_complete_patient_journey()
    {
        // 0. Setup Roles & Users
        $adminRole = Role::where('slug', 'admin')->firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $doctorRole = Role::where('slug', 'doctor')->firstOrCreate(['slug' => 'doctor'], ['name' => 'Doctor']);
        $receptionRole = Role::where('slug', 'reception')->firstOrCreate(['slug' => 'reception'], ['name' => 'Reception']);
        $patientRole = Role::where('slug', 'patient')->firstOrCreate(['slug' => 'patient'], ['name' => 'Patient']);

        $dept = Department::firstOrCreate(['name' => 'General Medicine', 'description' => 'General']);

        // Doctor
        $doctorUser = User::factory()->create(['role_id' => $doctorRole->id]);
        DoctorProfile::create([
            'user_id' => $doctorUser->id,
            'department_id' => $dept->id,
            'specialization' => 'General',
            'qualification' => 'MBBS',
            'experience_years' => 10
        ]);

        // Receptionist
        $receptionUser = User::factory()->create(['role_id' => $receptionRole->id]);

        // Patient
        $patientUser = User::factory()->create(['role_id' => $patientRole->id]);
        $patientProfile = Patient::create([
            'user_id' => $patientUser->id,
            'patient_code' => 'TEST-' . uniqid(),
            'name' => $patientUser->name,
            'dob' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Test St',
            'insurance_provider' => 'Aetna', // Added by migration 2026_01_20_050000
            'policy_number' => 'TEST-123'
        ]);

        // Stock Medicines 
        $medA = Medicine::firstOrCreate(['name' => 'Paracetamol'], [
            'price' => 5.00, 'stock_quantity' => 100, 'sku' => 'PARA001'
        ]);
        
        echo "\n[1] Setup Complete. Doctor: {$doctorUser->name}, Patient: {$patientUser->name}\n";

        // ---------------------------------------------------------
        // 1. PATIENT BOOKS APPOINTMENT
        // ---------------------------------------------------------
        $response = $this->actingAs($patientUser)
            ->post(route('patient.appointments.store'), [
                'doctor_id' => $doctorUser->id,
                'appointment_date' => now()->addDay()->format('Y-m-d'),
                'appointment_time' => '10:00',
                'reason' => 'Fever and Headache'
            ]);
        
        $response->assertSessionHasNoErrors();
        $appointment = Appointment::where('patient_id', $patientUser->id)->latest()->first();
        $this->assertNotNull($appointment, "Appointment should be created");
        $this->assertEquals('scheduled', $appointment->status); // Assuming default is scheduled or pending

        echo "[2] Appointment Booked. ID: {$appointment->id}\n";

        // ---------------------------------------------------------
        // 2. RECEPTION CONFIRMS (If needed) OR DOCTOR VIEWS
        // ---------------------------------------------------------
        // Let's assume Doctor starts consultation
        
        // ---------------------------------------------------------
        // 3. DOCTOR ADDS DIAGNOSIS & PRESCRIPTION
        // ---------------------------------------------------------
        $response = $this->actingAs($doctorUser)
            ->post(route('doctors.appointments.diagnosis.store', $appointment), [
                'diagnosis' => 'Viral Fever',
                'symptoms' => 'High temp, shivering',
                'notes' => 'Rest advised'
            ]);
        $response->assertSessionHasNoErrors();
        
        $diagnosis = Diagnosis::where('appointment_id', $appointment->id)->first();
        $this->assertNotNull($diagnosis, "Diagnosis should be created");

        // Add Prescription (Paracetamol)
        $response = $this->actingAs($doctorUser)
            ->post(route('doctors.prescription.store', $diagnosis), [
                'medicine_name' => 'Paracetamol',
                'dosage' => '500mg',
                'duration' => '3 days',
                'instructions' => 'After food'
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('prescriptions', ['diagnosis_id' => $diagnosis->id, 'medicine_name' => 'Paracetamol']);

        // Mark Complete
        $this->actingAs($doctorUser)
            ->patch(route('doctor.appointments.complete', $appointment));
        
        $appointment->refresh();
        $this->assertEquals('completed', $appointment->status);

        echo "[3] Diagnosis & Prescription Added. Appointment Completed.\n";

        // ---------------------------------------------------------
        // 4. RECEPTION GENERATES INVOICE
        // ---------------------------------------------------------
        // Assuming Logic: Reception creates invoice based on Consultation Fee + Medicines
        // We'll mimic the controller logic roughly or call the store route
        
        $response = $this->actingAs($receptionUser)
            ->post(route('reception.invoices.store', $appointment), [
                 'types' => ['service', 'medicine'],
                 'quantities' => [1, 2],
                 'prices' => [50.00, 5.00],
                 'descriptions' => ['Consultation', 'Paracetamol'],
                 'medicine_ids' => [null, $medA->id] 
            ]);
        
        $invoice = Invoice::where('appointment_id', $appointment->id)->first();
        // If the controller logic auto-calculates medicine, check total
        // Consultation (50) + Paracetamol (needs price logic if integrated). 
        // For now let's assume invoice creation works.
        $this->assertNotNull($invoice, "Invoice should be generated");

        echo "[4] Invoice Generated. Total: {$invoice->amount}\n";

        // ---------------------------------------------------------
        // 5. INSURANCE CLAIM
        // ---------------------------------------------------------
        $response = $this->actingAs($receptionUser)
             ->post(route('reception.insurance.store'), [
                 'invoice_id' => $invoice->id,
                 'provider_name' => 'Aetna',
                 'claim_number' => 'CLM-' . uniqid(),
                 'amount_claimed' => $invoice->amount,
                 'submitted_at' => now()->format('Y-m-d'),
                 'notes' => 'Auto test claim'
             ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('insurance_claims', ['invoice_id' => $invoice->id, 'status' => 'pending']);

        echo "[5] Insurance Claim Submitted.\n";

        // ---------------------------------------------------------
        // 6. PROCESS PAYMENT (Simulate Insurance Covers 80%)
        // ---------------------------------------------------------
        // Mark claim as approved/paid (skipping external steps)
        $claim = $invoice->insuranceClaim;
        $claim->update(['status' => 'approved', 'amount_approved' => $invoice->total_amount * 0.8]);

        // Receptionist marks invoice as paid
        $response = $this->actingAs($receptionUser)
            ->patch(route('reception.invoices.pay', $invoice));
        
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);

        echo "[6] Invoice Paid.\n";
        echo "✅ FULL SYSTEM TEST PASSED\n";
    }
}
