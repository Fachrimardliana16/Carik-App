<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiArsip extends Model
{
    protected $fillable = ['kode', 'nama', 'keterangan'];

    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class);
    }

    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
