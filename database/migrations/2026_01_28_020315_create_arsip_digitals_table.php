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
        Schema::create('arsip_digitals', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor_arsip')->unique();
            $table->string('kategori'); // SK, Peraturan, Notulen, Laporan
            $table->string('file_path');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_arsip');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_digitals');
    }
};
