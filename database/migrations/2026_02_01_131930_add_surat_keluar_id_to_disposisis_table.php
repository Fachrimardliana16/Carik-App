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
        Schema::table('disposisis', function (Blueprint $table) {
            $table->foreignId('surat_keluar_id')->nullable()->after('surat_masuk_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropForeign(['surat_keluar_id']);
            $table->dropColumn('surat_keluar_id');
        });
    }
};
