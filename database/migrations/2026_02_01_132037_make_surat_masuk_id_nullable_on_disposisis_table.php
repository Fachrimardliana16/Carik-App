<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to make a column nullable
        // This is a workaround since SQLite doesn't support ALTER COLUMN directly
        
        Schema::table('disposisis', function (Blueprint $table) {
            // Drop the foreign key first if it exists
            // SQLite doesn't have proper foreign key support for modifications
        });
        
        // Use raw SQL for SQLite to make the column nullable
        // SQLite workaround: disable foreign keys, create new table, copy data, drop old, rename new
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off;');
            
            // Create temp table with nullable surat_masuk_id
            DB::statement('
                CREATE TABLE disposisis_temp (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    surat_masuk_id INTEGER NULL,
                    surat_keluar_id INTEGER NULL,
                    dari_user_id INTEGER NOT NULL,
                    kepada_user_id INTEGER NOT NULL,
                    instruksi TEXT NOT NULL,
                    prioritas VARCHAR(255) DEFAULT "Biasa",
                    status VARCHAR(255) DEFAULT "Pending",
                    batas_waktu DATE NULL,
                    dibaca_pada DATETIME NULL,
                    selesai_pada DATETIME NULL,
                    catatan_penyelesaian TEXT NULL,
                    catatan_pengembalian TEXT NULL,
                    created_by INTEGER NULL,
                    updated_by INTEGER NULL,
                    deleted_by INTEGER NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    deleted_at DATETIME NULL
                );
            ');
            
            // Copy data
            DB::statement('
                INSERT INTO disposisis_temp 
                SELECT id, surat_masuk_id, surat_keluar_id, dari_user_id, kepada_user_id, instruksi, prioritas, status, batas_waktu, dibaca_pada, selesai_pada, catatan_penyelesaian, catatan_pengembalian, created_by, updated_by, deleted_by, created_at, updated_at, deleted_at
                FROM disposisis;
            ');
            
            // Drop old table
            DB::statement('DROP TABLE disposisis;');
            
            // Rename temp to original
            DB::statement('ALTER TABLE disposisis_temp RENAME TO disposisis;');
            
            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            // For MySQL/PostgreSQL
            Schema::table('disposisis', function (Blueprint $table) {
                $table->foreignId('surat_masuk_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be easily reversed for SQLite
    }
};
