<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add comprehensive indexes for performance
     */
    public function up(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('role_id');
            $table->index('status');
            $table->index(['role_id', 'status']); // Composite for role-based queries
        });

        // Patients table indexes
        Schema::table('patients', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('medical_record_number');
            $table->index(['first_name', 'last_name']); // Name search
            $table->index('date_of_birth');
            $table->index('created_at');
        });

        // Appointments table indexes (additional)
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index(['doctor_id', 'appointment_date']); // Doctor's schedule
            $table->index(['patient_id', 'status']); // Patient's appointments
            $table->index(['status', 'appointment_date']); // Filter by status and date
        });

        // Invoices table indexes (additional)
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('patient_id');
            $table->index('appointment_id');
            $table->index(['status', 'created_at']); // Payment tracking
            $table->index('total_amount');
        });

        // Prescriptions table indexes
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->index('patient_id');
            $table->index('diagnosis_id');
            $table->index('status');
            $table->index('created_at');
        });

        // Medicines table indexes
        Schema::table('medicines', function (Blueprint $table) {
            $table->index('name');
            $table->index('category');
            $table->index('stock_quantity'); // Low stock alerts
            $table->index(['stock_quantity', 'status']); // Active low stock
        });

        // Messages table indexes (chat)
        Schema::table('messages', function (Blueprint $table) {
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('is_read');
            $table->index(['receiver_id', 'is_read']); // Unread messages
            $table->index('created_at');
        });

        // Diagnoses table indexes
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->index('appointment_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('created_at');
        });

        // Patient vitals indexes
        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->index('patient_id');
            $table->index('appointment_id');
            $table->index(['patient_id', 'created_at']); // Vital history
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['role_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['role_id', 'status']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['medical_record_number']);
            $table->dropIndex(['first_name', 'last_name']);
            $table->dropIndex(['date_of_birth']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['doctor_id']);
            $table->dropIndex(['doctor_id', 'appointment_date']);
            $table->dropIndex(['patient_id', 'status']);
            $table->dropIndex(['status', 'appointment_date']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['total_amount']);
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['diagnosis_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category']);
            $table->dropIndex(['stock_quantity']);
            $table->dropIndex(['stock_quantity', 'status']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['sender_id']);
            $table->dropIndex(['receiver_id']);
            $table->dropIndex(['is_read']);
            $table->dropIndex(['receiver_id', 'is_read']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('diagnoses', function (Blueprint $table) {
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['doctor_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['patient_id', 'created_at']);
        });
    }
};
