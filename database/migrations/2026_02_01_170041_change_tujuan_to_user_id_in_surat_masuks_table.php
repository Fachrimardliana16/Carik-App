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
            // Drop the existing string column
            $table->dropColumn('tujuan');
        });
        
        Schema::table('surat_masuks', function (Blueprint $table) {
            // Add new foreign key column
            $table->unsignedBigInteger('tujuan_user_id')->nullable()->after('pengirim');
            $table->foreign('tujuan_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropForeign(['tujuan_user_id']);
            $table->dropColumn('tujuan_user_id');
        });
        
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->string('tujuan')->nullable()->after('pengirim');
        });
    }
};
