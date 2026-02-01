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
        Schema::create('klasifikasi_arsips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('klasifikasi_arsips')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->string('nama');
            $table->integer('level')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_arsips');
    }
};
