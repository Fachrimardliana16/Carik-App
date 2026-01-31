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
        Schema::table('splaners', function (Blueprint $table) {
            $table->foreignId('surat_masuk_id')->nullable()->after('status')->constrained('surat_masuks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('splaners', function (Blueprint $table) {
            $table->dropConstrainedForeignId('surat_masuk_id');
        });
    }
};
