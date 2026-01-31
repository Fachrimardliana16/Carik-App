<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\Notulensi;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Generate PDF for Surat Keluar
     */
    public static function printSuratKeluar(SuratKeluar $surat)
    {
        $pdf = Pdf::loadView('pdf.surat-keluar', [
            'surat' => $surat,
            'company' => [
                'name' => SettingsService::getCompanyName(),
                'address' => SettingsService::get('company_address'),
                'phone' => SettingsService::get('company_phone'),
                'email' => SettingsService::get('company_email'),
                'logo' => self::getLogoBase64(),
            ],
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Surat-Keluar-' . $surat->nomor_surat . '.pdf');
    }

    /**
     * Generate PDF for Disposisi
     */
    public static function printDisposisi(Disposisi $disposisi)
    {
        $pdf = Pdf::loadView('pdf.disposisi', [
            'disposisi' => $disposisi,
            'company' => [
                'name' => SettingsService::getCompanyName(),
                'address' => SettingsService::get('company_address'),
                'phone' => SettingsService::get('company_phone'),
                'email' => SettingsService::get('company_email'),
                'logo' => self::getLogoBase64(),
            ],
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Disposisi-' . $disposisi->id . '.pdf');
    }

    /**
     * Generate PDF for Splaner Report (All Upcoming)
     */
    public static function printSplanerReport()
    {
        $agendas = \App\Models\Splaner::where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.splaner-report', [
            'agendas' => $agendas,
            'company' => [
                'name' => SettingsService::getCompanyName(),
                'address' => SettingsService::get('company_address'),
                'logo' => self::getLogoBase64(),
            ],
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Report-Agenda-' . date('Ymd') . '.pdf');
    }

    /**
     * Generate PDF for Notulensi
     */
    public static function printNotulensi(Notulensi $notulensi)
    {
        $pdf = Pdf::loadView('pdf.notulensi', [
            'notulensi' => $notulensi,
            'company' => [
                'name' => SettingsService::getCompanyName(),
                'address' => SettingsService::get('company_address'),
                'phone' => SettingsService::get('company_phone'),
                'email' => SettingsService::get('company_email'),
                'logo' => self::getLogoBase64(),
            ],
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Notulensi-' . $notulensi->tanggal->format('Ymd') . '.pdf');
    }

    /**
     * Helper to get logo as base64 for PDF
     * DomPDF sometimes struggles with obscure paths, base64 is safer
     */
    protected static function getLogoBase64(): ?string
    {
        $path = SettingsService::get('logo_light');
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = Storage::disk('public')->get($path);
        
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
