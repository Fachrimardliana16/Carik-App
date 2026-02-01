<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Services\SettingsService;

class LandingPage extends Component
{
    public $search = '';
    public $resultMasuk = null;
    public $resultKeluar = null;
    public $companyName = '';
    public $logoLight = '';
    public $primaryColor = '';
    public $activeTab = 'tracking';
    public $verificationCode = '';
    public $verificationResult = null;

    public function mount()
    {
        $this->companyName = SettingsService::getCompanyName();
        $this->logoLight = SettingsService::getLogoLight();
        $this->primaryColor = SettingsService::getPrimaryColor();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resultMasuk = null;
        $this->resultKeluar = null;
        $this->verificationResult = null;
    }

    public function performSearch()
    {
        if (empty($this->search)) {
            $this->resultMasuk = null;
            $this->resultKeluar = null;
            return;
        }

        $this->resultMasuk = SuratMasuk::with(['disposisis.kepadaUser', 'disposisis.dariUser', 'statusSurat', 'tujuanUser'])
            ->where('nomor_surat', 'like', "%{$this->search}%")
            ->orWhere('nomor_agenda', 'like', "%{$this->search}%")
            ->first();

        $this->resultKeluar = SuratKeluar::with(['statusSurat'])
            ->where('nomor_surat', 'like', "%{$this->search}%")
            ->first();

        if (!$this->resultMasuk && !$this->resultKeluar) {
            session()->flash('error', 'Surat tidak ditemukan.');
        } else {
            $this->dispatch('scroll-to-results');
        }
    }

    public function verifyDocument()
    {
        if (empty($this->verificationCode)) {
            $this->verificationResult = null;
            return;
        }

        $this->verificationResult = SuratKeluar::with(['penandatangan', 'statusSurat'])
            ->where('qr_code', $this->verificationCode)
            ->first();

        if (!$this->verificationResult) {
            session()->flash('verification_error', 'Kode validasi tidak ditemukan atau dokumen tidak sah.');
        } else {
            $this->dispatch('scroll-to-results');
        }
    }

    public function render()
    {
        return view('livewire.landing-page')->layout('layouts.guest');
    }
}
