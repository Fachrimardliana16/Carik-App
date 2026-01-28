<?php

namespace App\Observers;

use App\Models\SuratKeluar;
use App\Services\QrCodeService;
use Illuminate\Support\Str;

class SuratKeluarObserver
{
    /**
     * Handle the SuratKeluar "created" event.
     */
    public function creating(SuratKeluar $suratKeluar): void
    {
        // Generate UUID for unique public validation code if not exists
        if (empty($suratKeluar->qr_code)) {
            $suratKeluar->qr_code = (string) Str::uuid();
        }
    }

    /**
     * Handle the SuratKeluar "created" event.
     */
    public function created(SuratKeluar $suratKeluar): void
    {
        $this->generateQrImage($suratKeluar);
    }

    /**
     * Handle the SuratKeluar "updated" event.
     */
    public function updated(SuratKeluar $suratKeluar): void
    {
        if ($suratKeluar->isDirty('qr_code') || !$suratKeluar->file_path_qr) {
            $this->generateQrImage($suratKeluar);
        }
    }

    protected function generateQrImage(SuratKeluar $suratKeluar): void
    {
        if (empty($suratKeluar->qr_code)) {
            return;
        }

        $validationUrl = QrCodeService::getValidationUrl($suratKeluar->qr_code);
        $filename = 'qr-codes/' . $suratKeluar->id . '.png';

        QrCodeService::generate($validationUrl, $filename);
    }
}
