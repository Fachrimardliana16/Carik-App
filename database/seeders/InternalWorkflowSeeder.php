<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\Splaner;
use App\Models\StatusSurat;
use App\Models\KlasifikasiArsip;

class InternalWorkflowSeeder extends Seeder
{
    /**
     * Alur Internal Surat Keluar:
     * 1. Karyawan membuat Surat Keluar Internal ke User 1
     * 2. Surat diteruskan ke Sekretariat untuk direview
     * 3. Sekretariat mendisposisi ke Direktur untuk persetujuan
     * 4. Direktur menyetujui dan mendisposisi kembali ke User 1
     * 5. User 1 menerima dan menambahkan ke S-Planer
     */
    public function run(): void
    {
        // Get actors
        $karyawan = User::where('username', 'karyawan')->first();
        $sekretariat = User::where('username', 'sekretaris')->first();
        $direktur = User::where('username', 'direktur')->first();
        $user1 = User::where('username', 'user1')->first();

        // If user1 doesn't exist, try to find any non-admin user
        if (!$user1) {
            $user1 = User::whereNotIn('username', ['admin', 'superadmin', 'direktur', 'sekretariat', 'karyawan'])
                ->first();
        }

        // Create user1 if not exists
        if (!$user1) {
            $user1 = User::create([
                'name' => 'User 1 (Unit Kerja)',
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        if (!$karyawan || !$sekretariat || !$direktur) {
            $this->command->error('Tidak dapat menemukan user yang diperlukan (karyawan, sekretariat, direktur). Jalankan UserSeeder dulu.');
            return;
        }

        $statusDraft = StatusSurat::where('nama', 'Draft')->first();
        $statusReview = StatusSurat::where('nama', 'Review')->first();
        $statusSigned = StatusSurat::where('nama', 'Signed')->first();
        $statusSent = StatusSurat::where('nama', 'Sent')->first();
        $klasifikasi = KlasifikasiArsip::first();

        // ============================================
        // STEP 1: Karyawan membuat Surat Keluar Internal
        // ============================================
        $this->command->info('Step 1: Karyawan membuat Surat Keluar Internal ke User 1...');
        
        $nomorSurat = 'INT-' . date('Y') . '-001';
        $suratKeluar = SuratKeluar::updateOrCreate(
            ['nomor_surat' => $nomorSurat],
            [
                'tanggal_surat' => now(),
                'is_internal' => true,
                'tujuan_user_id' => $user1->id,
                'tujuan' => $user1->name,
                'perihal' => 'Undangan Rapat Koordinasi Divisi',
                'sifat' => 'Penting',
                'status' => 'Draft',
                'isi_surat' => '<p>Dengan hormat,</p><p>Diundang untuk menghadiri rapat koordinasi divisi pada:</p><ul><li>Hari/Tanggal: ' . now()->addDays(3)->format('l, d F Y') . '</li><li>Waktu: 09:00 - 11:00 WIB</li><li>Tempat: Ruang Rapat Lt. 2</li></ul><p>Kehadiran Bapak/Ibu sangat diharapkan.</p>',
                'tembusan' => "1. Arsip\n2. Sekretariat",
                'penandatangan_id' => $direktur->id,
                'klasifikasi_arsip_id' => $klasifikasi?->id,
                'status_surat_id' => $statusDraft?->id,
                'created_by' => $karyawan->id,
            ]
        );

        $this->command->info("   Surat dibuat: {$suratKeluar->nomor_surat}");

        // ============================================
        // STEP 2: Karyawan mendisposisi ke Sekretariat untuk review
        // ============================================
        $this->command->info('Step 2: Karyawan mendisposisi ke Sekretariat untuk review...');
        
        $disposisi1 = Disposisi::create([
            'surat_keluar_id' => $suratKeluar->id,
            'dari_user_id' => $karyawan->id,
            'kepada_user_id' => $sekretariat->id,
            'instruksi' => 'Mohon review dan teruskan ke Bapak Direktur untuk persetujuan.',
            'prioritas' => 'Segera',
            'status' => 'Pending',
            'created_by' => $karyawan->id,
        ]);

        $this->command->info("   Disposisi 1 dibuat: Karyawan -> Sekretariat");

        // Update surat status
        $suratKeluar->update([
            'status' => 'Review',
            'status_surat_id' => $statusReview?->id,
        ]);

        // ============================================
        // STEP 3: Sekretariat review dan teruskan ke Direktur
        // ============================================
        $this->command->info('Step 3: Sekretariat mereview dan meneruskan ke Direktur...');

        // Mark disposisi 1 as read
        $disposisi1->update([
            'status' => 'Dibaca',
            'dibaca_pada' => now(),
        ]);

        // Sekretariat creates new disposisi to Direktur
        $disposisi2 = Disposisi::create([
            'surat_keluar_id' => $suratKeluar->id,
            'dari_user_id' => $sekretariat->id,
            'kepada_user_id' => $direktur->id,
            'instruksi' => 'Surat undangan rapat sudah direview. Mohon persetujuan untuk ditandatangani.',
            'prioritas' => 'Segera',
            'status' => 'Pending',
            'created_by' => $sekretariat->id,
        ]);

        // Mark disposisi 1 as done
        $disposisi1->update([
            'status' => 'Selesai',
            'selesai_pada' => now(),
            'catatan_penyelesaian' => 'Sudah diteruskan ke Direktur.',
        ]);

        $this->command->info("   Disposisi 2 dibuat: Sekretariat -> Direktur");

        // ============================================
        // STEP 4: Direktur menyetujui dan menandatangani
        // ============================================
        $this->command->info('Step 4: Direktur menyetujui dan menandatangani...');

        // Mark disposisi 2 as read
        $disposisi2->update([
            'status' => 'Dibaca',
            'dibaca_pada' => now(),
        ]);

        // Direktur signs the document (simplified - actual signing uses DigitalSignatureService)
        $dataToSign = $suratKeluar->nomor_surat . '|' . $suratKeluar->tanggal_surat->format('Y-m-d') . '|' . $suratKeluar->perihal;
        
        if ($direktur->private_key) {
            try {
                $signature = \App\Services\DigitalSignatureService::sign($direktur, $dataToSign);
                $suratKeluar->update([
                    'qr_code' => $signature,
                    'signature_hash' => $signature,
                    'signed_at' => now(),
                    'status' => 'Selesai',
                    'status_surat_id' => $statusSigned?->id ?? $statusSent?->id,
                ]);
                $this->command->info("   Surat ditandatangani secara digital.");
            } catch (\Exception $e) {
                $this->command->warn("   Gagal tanda tangan digital: " . $e->getMessage());
                $suratKeluar->update([
                    'signed_at' => now(),
                    'status' => 'Selesai',
                    'status_surat_id' => $statusSigned?->id ?? $statusSent?->id,
                ]);
            }
        } else {
            $suratKeluar->update([
                'signed_at' => now(),
                'status' => 'Selesai',
                'status_surat_id' => $statusSent?->id,
            ]);
        }

        // Direktur disposisi final ke User 1
        $disposisi3 = Disposisi::create([
            'surat_keluar_id' => $suratKeluar->id,
            'dari_user_id' => $direktur->id,
            'kepada_user_id' => $user1->id,
            'instruksi' => 'Surat undangan sudah disetujui dan ditandatangani. Mohon hadir sesuai jadwal.',
            'prioritas' => 'Biasa',
            'status' => 'Pending',
            'created_by' => $direktur->id,
        ]);

        // Mark disposisi 2 as done
        $disposisi2->update([
            'status' => 'Selesai',
            'selesai_pada' => now(),
            'catatan_penyelesaian' => 'Disetujui dan ditandatangani. Diteruskan ke penerima.',
        ]);

        $this->command->info("   Disposisi 3 dibuat: Direktur -> User 1");

        // ============================================
        // STEP 5: User 1 menerima dan menambahkan ke S-Planer
        // ============================================
        $this->command->info('Step 5: User 1 menerima dan menambahkan ke S-Planer...');

        // Mark disposisi 3 as read
        $disposisi3->update([
            'status' => 'Dibaca',
            'dibaca_pada' => now(),
        ]);

        // User 1 creates S-Planer entry for the meeting
        $rapatDate = now()->addDays(3);
        $splaner = Splaner::create([
            'surat_keluar_id' => $suratKeluar->id,
            'title' => 'Rapat Koordinasi Divisi',
            'start_time' => $rapatDate->copy()->setTime(9, 0),
            'end_time' => $rapatDate->copy()->setTime(11, 0),
            'location' => 'Ruang Rapat Lt. 2',
            'status' => 'Dijadwalkan',
            'user_id' => $user1->id,
            'created_by' => $user1->id,
            'description' => "Rapat sesuai undangan {$suratKeluar->nomor_surat}.\nAgenda: Koordinasi divisi.",
        ]);

        // Mark disposisi 3 as done
        $disposisi3->update([
            'status' => 'Selesai',
            'selesai_pada' => now(),
            'catatan_penyelesaian' => 'Sudah dijadwalkan di S-Planer.',
        ]);

        $this->command->info("   S-Planer dibuat: {$splaner->title}");
        $this->command->info('');
        $this->command->info('=== WORKFLOW SELESAI ===');
        $this->command->info("Surat: {$suratKeluar->nomor_surat}");
        $this->command->info("S-Planer: {$splaner->title} ({$splaner->start_time->format('d M Y H:i')})");
    }
}
