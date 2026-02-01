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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // branding, system, integrations
            $table->timestamps();
        });
        
        // Seed default branding
        DB::table('settings')->insert([
            ['key' => 'app_name', 'value' => 'HealthFlow CRM', 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_logo', 'value' => null, 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'primary_color', 'value' => '#4f46e5', 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
