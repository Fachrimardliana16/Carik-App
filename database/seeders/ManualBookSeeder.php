<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManualBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            [
                'title' => '1. Pendahuluan & Dashboard',
                'order' => 10,
                'content' => '
                    <h3>Selamat Datang di Sistem Informasi Pengelolaan Dokumen (SIPD)</h3>
                    <p>Sistem ini dirancang untuk mendigitalkan proses persuratan di lingkungan instansi Anda. Berikut adalah fitur utama yang tersedia:</p>
                    <ul>
                        <li><strong>Surat Masuk:</strong> Pencatatan dan alur disposisi surat dari pihak eksternal.</li>
                        <li><strong>Surat Keluar:</strong> Pembuatan surat dinas, penandatanganan elektronik, dan pengarsipan.</li>
                        <li><strong>Disposisi:</strong> Alur distribusi tugas dari pimpinan ke unit kerja secara real-time.</li>
                        <li><strong>Tracking Publik:</strong> Masyarakat dapat melacak status surat mereka melalui Landing Page.</li>
                    </ul>
                    <p><strong>Dashboard Utama</strong> menampilkan ringkasan statistik surat masuk, surat keluar, dan status disposisi yang memerlukan perhatian Anda.</p>
                ',
            ],
            [
                'title' => '2. Manajemen Surat Masuk',
                'order' => 20,
                'content' => '
                    <h3>Alur Surat Masuk</h3>
                    <ol>
                        <li><strong>Input Data:</strong> Operator atau Sekretariat menginput data surat (Nomor Surat, Pengirim, Perihal, File PDF) melalui menu <em>Surat Masuk</em>.</li>
                        <li><strong>Verifikasi:</strong> Pastikan data sesuai dengan fisik surat.</li>
                        <li><strong>Disposisi Awal:</strong> Surat diteruskan ke Pimpinan (Direktur/Kepala Dinas) untuk arahan lebih lanjut.</li>
                    </ol>
                    <h3>Fitur Penting:</h3>
                    <ul>
                        <li><strong>Status Tracking:</strong> Setiap pergeseran surat tercatat dalam history.</li>
                        <li><strong>Notifikasi:</strong> Unit tujuan akan menerima notifikasi saat menerima disposisi.</li>
                    </ul>
                ',
            ],
            [
                'title' => '3. Manajemen Surat Keluar',
                'order' => 30,
                'content' => '
                    <h3>Pembuatan Surat Keluar</h3>
                    <p>Sistem menyediakan fitur cetak template otomatis:</p>
                    <ol>
                        <li>Pilih menu <strong>Surat Keluar > Tambah Baru</strong>.</li>
                        <li>Pilih <strong>Template Surat</strong> yang sesuai.</li>
                        <li>Isi variabel surat (Kepada, Isi Surat, Tembusan).</li>
                        <li>Sistem akan menyusun format surat sesuai standar tata naskah dinas.</li>
                    </ol>
                    <h3>Tanda Tangan Elektronik (TTE)</h3>
                    <p>Setelah surat disetujui, Pejabat Penandatangan dapat membubuhkan TTE (QR Code) yang valid secara sistem.</p>
                ',
            ],
            [
                'title' => '4. Alur Disposisi',
                'order' => 40,
                'content' => '
                    <h3>Menerima & Meneruskan Disposisi</h3>
                    <p>Saat surat masuk ke meja Anda:</p>
                    <ul>
                        <li><strong>Terima:</strong> Klik tombol terima untuk menyatakan surat sudah dibaca.</li>
                        <li><strong>Tindak Lanjut:</strong> Lakukan aksi sesuai instruksi (misal: Balas Surat, Arsipkan, atau Teruskan ke Staff).</li>
                        <li><strong>Selesai:</strong> Tandai status menjadi <em>Selesai</em> jika proses telah rampung.</li>
                    </ul>
                    <div class="alert alert-info">
                        <strong>Catatan:</strong> Disposisi bersifat hierarkis, pastikan instruksi tertulis jelas pada kolom Catatan.
                    </div>
                ',
            ],
            [
                'title' => '5. Layanan Publik (Tracking & Verifikasi)',
                'order' => 50,
                'content' => '
                    <h3>Fitur untuk Masyarakat / Pihak Eksternal</h3>
                    <p>Sistem ini dilengkapi dengan Halaman Depan (Landing Page) yang dapat diakses publik:</p>
                    <ul>
                        <li><strong>Lacak Surat:</strong> Pengirim surat dapat memasukkan Nomor Registrasi untuk mengetahui posisi dokumen mereka.</li>
                        <li><strong>Cek Keaslian Surat (Scan QR):</strong> Dokumen yang diterbitkan sistem memiliki QR Code. Gunakan menu <strong>Verifikasi QR</strong> di halaman depan untuk memindai dan memvalidasi keaslian dokumen.</li>
                    </ul>
                ',
            ],
            [
                'title' => '6. Pengaturan Akun & Instansi',
                'order' => 60,
                'content' => '
                    <h3>Profil Pengguna</h3>
                    <p>Anda dapat mengubah password dan foto profil melalui menu pojok kanan atas.</p>
                    <h3>Pengaturan Instansi (Khusus Admin)</h3>
                    <p>Buka menu <strong>Company Settings</strong> untuk mengubah:</p>
                    <ul>
                        <li>Nama & Logo Instansi.</li>
                        <li>Alamat & Kontak (akan muncul di Kop Surat).</li>
                        <li>Warna Tema Aplikasi.</li>
                    </ul>
                ',
            ],
        ];

        foreach ($guides as $guide) {
            \App\Models\ManualBook::create([
                'title' => $guide['title'],
                'slug' => \Illuminate\Support\Str::slug($guide['title']),
                'content' => $guide['content'],
                'order' => $guide['order'],
                'is_active' => true,
            ]);
        }
    }
}
