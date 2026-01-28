<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidationController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/validate/{code}', [ValidationController::class, 'validateDocument'])->name('val.document');
