---
trigger: always_on
---

# Project Instructions: Sistem Informasi Persuratan Digital (SIPD)

## 1. Core Architecture & Tech Stack
- **Framework:** Laravel 11 dengan Filament v3.
- **Database:** SQLite dengan Indexing ketat pada: `nomor_surat`, `status`, `tanggal_surat`, `created_by`.
- **Standard Columns:** Wajib ada `softDeletes()` dan audit columns (`created_by`, `updated_by`, `deleted_by`).
- **N+1 Prevention:** Otomatisasi Eager Loading pada setiap Filament Resource.
- **Audit Trail:** Implementasikan `spatie/laravel-activitylog` dan `rappasoft/laravel-authentication-log`.

## 2. Branding & Configuration (Company Settings)
- **Settings Page:** Buat halaman pengaturan global (menggunakan `filament-settings-hub` atau custom page) untuk mengelola:
    - Nama Instansi / Perusahaan.
    - Logo Utama (Light/Dark Mode).
    - Favicon & Brand Color (Primary Color).
    - Alamat & Kontak Instansi (untuk Header Surat).
- **Dynamic Branding:** Semua UI (Logo di Sidebar & Login) dan Header Report PDF harus mengambil data dari halaman Settings ini.

## 3. Advanced Workflow & Tracking
- **Sistem Disposisi:** Alur: Security -> Sekretariat -> Direktur -> Unit Tujuan.
- **Acting Officer (Plh):** Fitur delegasi wewenang tanda tangan saat pejabat utama absen.
- **Timeline Tracker:** Visual progress surat di halaman View (Meja siapa, statusnya apa).
- **Quick Templates:** Pembuatan surat otomatis dari template Blade/HTML.

## 4. Keamanan & QR Code Branding
- **Digital Signature:** Tanda tangan elektronik berbasis OpenSSL.
- **Branded QR Code:** Generate QR Code menggunakan library `simplesoftwareio/simple-qrcode`.
    - **Logo in QR:** QR Code wajib memiliki logo instansi di bagian tengah (eye-catching).
    - **Custom Style:** Sesuaikan warna QR Code dengan brand color perusahaan.
- **Validation Page:** Route publik untuk cek keaslian dokumen via scan QR.

## 5. Standar UI/UX (Premium Look)
- **Layouting:** Gunakan `Section`, `Grid`, `Tabs`, dan `ActionGroup` pada tabel.
- **Reporting:** Download PDF/Excel per-filter dan per-item dengan header/kop surat dinamis.
- **Error Handling:** Pesan error dalam Bahasa Indonesia yang user-friendly via Toast.
- **Notification:** Database notification (Bell Icon) untuk setiap update status/disposisi.

## 6. Panel & Role Management
- **Two-Panel System:** Panel `Admin` (Staff/Super Admin) dan Panel `App` (User Umum).
- **Auth:** Login menggunakan **Username**. Registrasi manual hanya oleh Super Admin.
- **Role (Shield):** Super Admin, Operator/Sekretariat, User/Unit.

## 7. Resources Required
- Surat Masuk, Surat Keluar, Disposisi, Tembusan, DS Surta (Digital Signature), Review Surat, Arsip Digital, Splaner (Calendar Direktur), Activity Logs, User & Role Management, Company Settings.