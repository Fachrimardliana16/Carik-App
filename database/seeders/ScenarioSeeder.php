<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get Actors
        $direktur = \App\Models\User::where('username', 'direktur')->first();
        if (!$direktur) return;
        
        $template = \App\Models\TemplateSurat::where('name', 'Surat Himbauan')->first();
        $klasifikasi = \App\Models\KlasifikasiArsip::first(); // Just pick first one
        $statusDraft = \App\Models\StatusSurat::where('nama', 'Draft')->first();
        $statusSent = \App\Models\StatusSurat::where('nama', 'Sent')->first();

        // 2. Create Surat Keluar (Draft)
        $date = now()->addDays(2); // Date: Feb 2nd scenario (or just 2 days from now)
        $nomorSurat = '001/HIMBAUAN/II/' . date('Y');
        
        // Prepare content with placeholders replaced (simulation of what UI does or just raw content)
        // Ideally we save the raw template content if the system replaces on render, OR we save the rendered content?
        // In Resource `afterStateUpdated`, it sets `isi_surat` from `template->content`.
        // So we should save the template content (with placeholders).
        // Wait, usually we replace placeholders BEFORE saving if it's a one-off letter, OR we keep them and replace on PDF generation.
        // The resource logic just copies `content`.
        // Let's assume we copy content.
        
        $surat = \App\Models\SuratKeluar::updateOrCreate(
            ['nomor_surat' => $nomorSurat],
            [
            'tanggal_surat' => $date,
            'tujuan' => 'Seluruh Karyawan',
            'perihal' => 'Himbauan Penggunaan Pakaian Olahraga',
            'sifat' => 'Biasa',
            'status' => 'Menunggu TTD', // Simulate it's ready for signing
            'isi_surat' => $template ? $template->content : '<p>Harap gunakan pakaian olahraga.</p>',
            'tembusan' => "1. Arsip\n2. HRD",
            'penandatangan_id' => $direktur->id,
            'klasifikasi_arsip_id' => $klasifikasi?->id,
            'status_surat_id' => $statusDraft?->id,
            'created_by' => $direktur->id, // Created by Direktur directly or Sekretaris
            'template_id' => $template?->id,
        ]);

        // 3. Simulate Signing (TTE)
        // We need to generate hash using DigitalSignatureService
        // But since we are seeding, we can mock or use the service if keys exist.
        // UserSeeder generated keys for 'direktur'.
        
        if ($direktur->private_key) {
             // Mock signing
             $dataToSign = $surat->nomor_surat . '|' . $surat->tanggal_surat->format('Y-m-d') . '|' . $surat->perihal;
             $signature = \App\Services\DigitalSignatureService::sign($direktur, $dataToSign);
             
             $surat->update([
                 'qr_code' => $signature, // This might trigger observer again? Observer "saved". Yes.
                 // But QR service is fixed now.
                 'signature_hash' => $signature,
                 'signed_at' => now(),
                 'status' => 'Selesai',
                 'status_surat_id' => $statusSent?->id,
             ]);
        }

        // 4. Create S-Planer Agenda
        try {
            \App\Models\Splaner::create([
                'surat_keluar_id' => $surat->id,
                'title' => 'Kegiatan: ' . $surat->perihal,
                'start_time' => $date->setTime(9, 0), // 09:00 AM
                'end_time' => $date->setTime(11, 0),
                'location' => 'Kantor Pusat / Virtual',
                'status' => 'Dijadwalkan',
                'user_id' => $direktur->id,
                'created_by' => $direktur->id,
                'description' => 'Sesuai surat himbauan ' . $surat->nomor_surat,
            ]);
        } catch (\Exception $e) {
            echo "Failed to create Splaner: " . $e->getMessage() . PHP_EOL;
        }
        
    }
}
