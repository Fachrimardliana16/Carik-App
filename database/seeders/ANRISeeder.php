<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiArsip;
use App\Models\StatusSurat;

class ANRISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Classification Codes based on ANRI No. 4/2021
        $klasifikasi = [
            ['kode' => 'HK', 'nama' => 'Hukum', 'keterangan' => 'Urusan Hukum'],
            ['kode' => 'PR', 'nama' => 'Perencanaan', 'keterangan' => 'Urusan Perencanaan'],
            ['kode' => 'UM', 'nama' => 'Umum', 'keterangan' => 'Urusan Umum'],
            ['kode' => 'KP', 'nama' => 'Kepegawaian', 'keterangan' => 'Urusan Kepegawaian'],
            ['kode' => 'KU', 'nama' => 'Keuangan', 'keterangan' => 'Urusan Keuangan'],
            ['kode' => 'OT', 'nama' => 'Organisasi dan Tata Laksana', 'keterangan' => 'Urusan Organisasi'],
            ['kode' => 'HM', 'nama' => 'Hubungan Masyarakat', 'keterangan' => 'Urusan Hubungan Masyarakat'],
        ];

        foreach ($klasifikasi as $item) {
            KlasifikasiArsip::updateOrCreate(['kode' => $item['kode']], $item);
        }

        // Required statuses from user request: draft, review, Signed, Sent, Archived
        $statuses = [
            ['nama' => 'Draft', 'urutan' => 1, 'warna' => 'gray', 'is_default' => true],
            ['nama' => 'Review', 'urutan' => 2, 'warna' => 'warning', 'is_default' => false],
            ['nama' => 'Signed', 'urutan' => 3, 'warna' => 'success', 'is_default' => false],
            ['nama' => 'Sent', 'urutan' => 4, 'warna' => 'info', 'is_default' => false],
            ['nama' => 'Archived', 'urutan' => 5, 'warna' => 'primary', 'is_default' => false],
        ];

        foreach ($statuses as $status) {
            StatusSurat::updateOrCreate(['nama' => $status['nama']], $status);
        }
    }
}
