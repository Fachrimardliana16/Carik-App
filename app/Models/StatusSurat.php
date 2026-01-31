<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusSurat extends Model
{
    protected $fillable = ['nama', 'urutan', 'warna', 'is_default'];

    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class);
    }

    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
