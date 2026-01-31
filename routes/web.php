<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidationController;

use App\Livewire\LandingPage;
use App\Livewire\PublicSuratMasuk;

Route::get('/', LandingPage::class);
Route::get('/input-surat-masuk', PublicSuratMasuk::class)->name('public.surat-masuk');

Route::get('/validate/{code}', [ValidationController::class, 'validateDocument'])->name('val.document');

Route::middleware(['auth'])->group(function () {
    Route::get('/download-secure', [\App\Http\Controllers\FileController::class, 'download'])->name('file.download');
});
