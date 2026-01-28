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
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dari_user_id')->constrained('users');
            $table->foreignId('kepada_user_id')->constrained('users');
            $table->text('instruksi');
            $table->enum('prioritas', ['Biasa', 'Segera', 'Sangat Segera']);
            $table->enum('status', ['Pending', 'Dibaca', 'Diproses', 'Selesai'])->default('Pending');
            $table->date('batas_waktu')->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->text('catatan_penyelesaian')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['surat_masuk_id', 'kepada_user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
