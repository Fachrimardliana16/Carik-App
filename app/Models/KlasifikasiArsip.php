<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiArsip extends Model
{
    protected $fillable = ['kode', 'nama', 'keterangan', 'parent_id', 'level'];

    public function parent()
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(KlasifikasiArsip::class, 'parent_id');
    }

    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class);
    }

    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
