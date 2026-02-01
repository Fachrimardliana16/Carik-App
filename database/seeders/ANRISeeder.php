<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiArsip;
use App\Models\StatusSurat;
use Illuminate\Support\Facades\File;

class ANRISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Status Surat (Mandatory for the app)
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

        // 2. Parse and Seed Archive Classifications
        $filePath = base_path('.agent/rules/kode-surat.txt');
        
        if (!File::exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $data = [];
        $currentEntry = null;

        // Pattern for code: e.g. 000, 000.1, 000.1.1, 00.1 (even typos)
        $codePattern = '/^(\d+(\.\d+)*)\s+(.*)$/';

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip page markers and headers
            if (preg_match('/^-?\d+-?$/', $line) || 
                in_array($line, ['LAMPIRAN', 'TENTANG', 'KODE KLASIFIKASI ARSIP']) ||
                str_contains($line, 'PERATURAN WALIKOTA') ||
                str_contains($line, 'NOMOR 30 TAHUN 2023')) {
                continue;
            }

            if (preg_match($codePattern, $line, $matches)) {
                // New entry
                if ($currentEntry) {
                    $data[] = $currentEntry;
                }
                $currentEntry = [
                    'kode' => $matches[1],
                    'nama' => trim($matches[3]),
                ];
            } elseif ($currentEntry) {
                // Continuation of previous entry name
                // Basic cleanup of characters usually found in multiline text
                $cleanLine = trim($line);
                if ($cleanLine !== '') {
                    $currentEntry['nama'] .= ' ' . $cleanLine;
                }
            }
        }
        
        // Add the last one
        if ($currentEntry) {
            $data[] = $currentEntry;
        }

        $this->command->info("Found " . count($data) . " classification entries. Seeding...");

        $codeToId = [];

        // Sort data by code length/nesting to ensure parents are created first
        // However, the file is usually already in order.
        
        foreach ($data as $item) {
            $kode = $item['kode'];
            $nama = $item['nama'];
            
            // Determine level
            $level = substr_count($kode, '.') + 1;
            
            // Determine parent code
            $parentCode = null;
            if ($level > 1) {
                $parts = explode('.', $kode);
                array_pop($parts);
                $parentCode = implode('.', $parts);
            }

            $parentId = null;
            if ($parentCode && isset($codeToId[$parentCode])) {
                $parentId = $codeToId[$parentCode];
            }

            // Create record
            try {
                $record = KlasifikasiArsip::updateOrCreate(
                    ['kode' => $kode],
                    [
                        'nama' => mb_convert_encoding($nama, 'UTF-8', 'UTF-8'),
                        'parent_id' => $parentId,
                        'level' => $level,
                    ]
                );
                
                $codeToId[$kode] = $record->id;
            } catch (\Exception $e) {
                // Some codes might be duplicate in the text but unique in DB
                // Or some typos lead to issues. We skip or log.
                // $this->command->warn("Skip duplicate or invalid code: {$kode}");
            }
        }

        $this->command->info("Archive Classification seeding completed.");
    }
}
