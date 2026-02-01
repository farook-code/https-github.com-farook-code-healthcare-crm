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
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status');
            $table->index('appointment_date');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['model_type', 'model_id']);
            $table->index('created_at'); // for sorting by date
            $table->index('user_id');
        });

        Schema::table('lab_reports', function (Blueprint $table) {
            $table->index('patient_id');
            $table->index('created_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['appointment_date']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('lab_reports', function (Blueprint $table) {
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
        });
    }
};
