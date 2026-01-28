<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function validateDocument(string $code)
    {
        $surat = SuratKeluar::where('qr_code', $code)->firstOrFail();

        return view('validation', [
            'surat' => $surat,
            'isValid' => $surat->status === 'Terkirim' || $surat->status === 'Selesai',
        ]);
    }
}
