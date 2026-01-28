<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, color, textarea
            $table->string('group')->default('general'); // general, branding, contact
            $table->timestamps();
        });

        // Seed default settings
        DB::table('company_settings')->insert([
            ['key' => 'company_name', 'value' => 'Sistem Informasi Persuratan Digital', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_address', 'value' => '', 'type' => 'textarea', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_phone', 'value' => '', 'type' => 'text', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email', 'value' => '', 'type' => 'text', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'logo_light', 'value' => '', 'type' => 'image', 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'logo_dark', 'value' => '', 'type' => 'image', 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'favicon', 'value' => '', 'type' => 'image', 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'primary_color', 'value' => '#3b82f6', 'type' => 'color', 'group' => 'branding', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
