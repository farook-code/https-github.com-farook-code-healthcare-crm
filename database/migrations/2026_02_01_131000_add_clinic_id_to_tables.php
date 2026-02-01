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
        $tables = [
            'users',
            'departments',
            'patients',
            'medicines',
            'appointments',
            'invoices',
            'settings',
            'system_alerts',
            'audit_logs'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'clinic_id')) {
                        $table->foreignId('clinic_id')->nullable()->after('id')->constrained('clinics')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'departments',
            'patients',
            'medicines',
            'appointments',
            'invoices',
            'settings',
            'system_alerts',
            'audit_logs'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['clinic_id']);
                    $table->dropColumn('clinic_id');
                });
            }
        }
    }
};
