<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. IPD: Wards & Beds
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., General Ward, ICU, Private
            $table->string('type')->default('general'); // general, icu, private, semi-private
            $table->text('description')->nullable();
            $table->string('floor_number')->nullable();
            $table->timestamps();
        });

        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained()->cascadeOnDelete();
            $table->string('bed_number');
            $table->string('type')->default('standard'); // standard, manual, electric, ventilator
            $table->decimal('daily_charge', 10, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->string('status')->default('available'); // available, occupied, maintenance
            $table->timestamps();
        });

        // 2. IPD: Admissions
        Schema::create('ipd_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete(); // Attending doctor
            $table->foreignId('bed_id')->nullable()->constrained()->nullOnDelete(); // Current bed
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->text('reason_for_admission')->nullable();
            $table->text('diagnosis')->nullable();
            $table->string('status')->default('admitted'); // admitted, discharged, transferred
            $table->text('discharge_notes')->nullable();
            $table->decimal('total_estimate', 12, 2)->nullable();
            $table->decimal('advance_payment', 12, 2)->default(0);
            $table->timestamps();
        });

        // 3. Operation Theaters (OT)
        Schema::create('operation_theaters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. OT-1 (General), OT-2 (Cardiac)
            $table->string('type')->nullable(); // Sterile, Major, Minor
            $table->string('status')->default('available'); // available, in_use, cleaning, maintenance
            $table->decimal('hourly_charge', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ot_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_theater_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_surgeon_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('scheduled_start');
            $table->dateTime('scheduled_end');
            $table->string('procedure_name');
            $table->string('priority')->default('scheduled'); // scheduled, urgent, emergency
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Insurance Management
        Schema::create('insurance_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Aetna, BlueCross
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('network_type')->default('network'); // network, out_of_network
            $table->timestamps();
        });

        Schema::create('patient_insurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('insurance_provider_id')->constrained()->cascadeOnDelete();
            $table->string('policy_number');
            $table->string('group_number')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('coverage_limit', 12, 2)->nullable();
            $table->string('status')->default('active'); // active, expired, suspended
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_insurances');
        Schema::dropIfExists('insurance_providers');
        Schema::dropIfExists('ot_bookings');
        Schema::dropIfExists('operation_theaters');
        Schema::dropIfExists('ipd_admissions');
        Schema::dropIfExists('beds');
        Schema::dropIfExists('wards');
    }
};
