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
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->foreignId('tujuan_user_id')->nullable()->after('tujuan')->constrained('users')->nullOnDelete();
            $table->boolean('is_internal')->default(false)->after('tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tujuan_user_id');
            $table->dropColumn('is_internal');
        });
    }
};
