<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable()->after('policy_number');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');

            // Medical History (Simple Text/JSON storage for now)
            $table->text('allergies')->nullable()->after('emergency_contact_relation');
            $table->text('chronic_conditions')->nullable()->after('allergies');
            $table->text('current_medications')->nullable()->after('chronic_conditions');
            $table->date('last_visit_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relation',
                'allergies',
                'chronic_conditions',
                'current_medications',
                'last_visit_date'
            ]);
        });
    }
};
