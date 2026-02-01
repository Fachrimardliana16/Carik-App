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
        Schema::table('surat_masuks', function (Blueprint $table) {
            // Re-define status column. Since SQLite has limited support for changing columns, 
            // and we want to change default and potentially enum values.
            // But usually modify works if we keep type similar.
            // We will change to string to allow 'Pending' easily and future flexibility.
            $table->string('status')->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            // Revert to original enum logic if needed, but risky with data.
            // We'll leave it as string but revert default if asked.
            $table->enum('status', ['Diterima', 'Didisposisi', 'Diproses', 'Selesai'])->default('Diterima')->change();
        });
    }
};
