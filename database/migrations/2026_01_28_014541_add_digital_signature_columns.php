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
        Schema::table('users', function (Blueprint $table) {
            $table->text('signature_public_key')->nullable()->after('password');
            $table->text('signature_private_key')->nullable()->after('signature_public_key');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->text('signature_hash')->nullable()->after('qr_code');
            $table->timestamp('signed_at')->nullable()->after('signature_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['signature_public_key', 'signature_private_key']);
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn(['signature_hash', 'signed_at']);
        });
    }
};
