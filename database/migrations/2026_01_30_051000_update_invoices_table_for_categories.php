<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->nullable()->change();
            $table->string('category')->default('opd')->after('patient_id'); // opd, ipd, pharmacy, lab
            $table->foreignId('ipd_admission_id')->nullable()->after('appointment_id')->constrained('ipd_admissions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->nullable(false)->change();
            $table->dropColumn(['category', 'ipd_admission_id']);
        });
    }
};
