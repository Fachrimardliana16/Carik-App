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
        Schema::create('notulensis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('tempat');
            $table->string('agenda');
            $table->string('pimpinan_rapat');
            $table->foreignId('notulis_id')->constrained('users');
            $table->json('peserta')->nullable();
            $table->text('isi_notulensi');
            $table->string('file_path')->nullable();
            $table->enum('status', ['Draft', 'Pending Approval', 'Approved', 'Rejected', 'Forwarded'])->default('Draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            
            // Audit columns
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notulensis');
    }
};
