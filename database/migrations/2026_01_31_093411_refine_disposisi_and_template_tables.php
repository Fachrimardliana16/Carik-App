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
            $table->text('catatan_pengembalian')->nullable()->after('catatan_penyelesaian');
        });

        Schema::table('template_surats', function (Blueprint $table) {
            $table->text('kop_surat')->nullable()->after('content');
            $table->string('logo_surat')->nullable()->after('kop_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropColumn('catatan_pengembalian');
        });

        Schema::table('template_surats', function (Blueprint $table) {
            $table->dropColumn(['kop_surat', 'logo_surat']);
        });
    }
};
