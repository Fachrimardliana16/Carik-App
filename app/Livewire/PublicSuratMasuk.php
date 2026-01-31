<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SuratMasuk;
use App\Models\StatusSurat;
use App\Services\SettingsService;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Storage;

class PublicSuratMasuk extends Component
{
    use WithFileUploads;

    public $nomor_surat;
    public $pengirim;
    public $perihal;
    public $isi_ringkas;
    public $tanggal_surat;
    public $file;
    
    // Captcha
    public $num1;
    public $num2;
    public $captcha_answer;
    public $user_answer;

    public $companyName = '';
    public $logoLight = '';
    public $primaryColor = '';

    public function mount()
    {
        $this->generateCaptcha();
        $this->companyName = SettingsService::getCompanyName();
        $this->logoLight = SettingsService::getLogoLight();
        $this->primaryColor = SettingsService::getPrimaryColor();
    }

    public function generateCaptcha()
    {
        $this->num1 = rand(1, 10);
        $this->num2 = rand(1, 10);
        $this->captcha_answer = $this->num1 + $this->num2;
    }

    public function save()
    {
        $this->validate([
            'nomor_surat' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'user_answer' => 'required|numeric',
        ]);

        if ($this->user_answer != $this->captcha_answer) {
            $this->addError('user_answer', 'Jawaban CAPTCHA salah.');
            $this->generateCaptcha();
            return;
        }

        $statusDraft = StatusSurat::where('nama', 'Draft')->first();

        // Use the encryption service
        $filePath = FileEncryptionService::encryptAndStore($this->file, 'surat-masuk');

        SuratMasuk::create([
            'nomor_surat' => $this->nomor_surat,
            'nomor_agenda' => 'AGD-' . date('YmdHis') . '-' . rand(10, 99),
            'pengirim' => $this->pengirim,
            'perihal' => $this->perihal,
            'tanggal_surat' => $this->tanggal_surat,
            'tanggal_diterima' => now(),
            'isi_ringkas' => $this->isi_ringkas,
            'file_path' => $filePath,
            'sifat' => 'Biasa', // Default for public
            'status' => 'Diterima', // Legacy enum match
            'status_surat_id' => $statusDraft?->id,
        ]);

        session()->flash('success', 'Surat berhasil dikirim ke bagian Sekretariat.');
        
        $this->reset(['nomor_surat', 'pengirim', 'perihal', 'isi_ringkas', 'tanggal_surat', 'file', 'user_answer']);
        $this->generateCaptcha();
    }

    public function render()
    {
        return view('livewire.public-surat-masuk')->layout('layouts.guest');
    }
}
