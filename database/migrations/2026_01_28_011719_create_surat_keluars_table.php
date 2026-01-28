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
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->enum('sifat', ['Biasa', 'Segera', 'Sangat Segera', 'Rahasia']);
            $table->enum('status', ['Draft', 'Menunggu TTD', 'Selesai', 'Terkirim'])->default('Draft');
            $table->text('isi_surat')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('penandatangan_id')->nullable()->constrained('users');
            $table->string('qr_code')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            // Indexes based on instructions
            $table->index(['nomor_surat', 'status', 'tanggal_surat', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
