<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasAuditColumns;

class SuratMasuk extends Model
{
    use SoftDeletes, LogsActivity, HasAuditColumns;

    protected $fillable = [
        'nomor_surat',
        'nomor_agenda',
        'tanggal_surat',
        'tanggal_diterima',
        'pengirim',
        'perihal',
        'sifat',
        'status',
        'isi_ringkas',
        'file_path',
        'klasifikasi_arsip_id',
        'status_surat_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_diterima' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor_surat', 'status', 'perihal'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class);
    }

    public function klasifikasiArsip()
    {
        return $this->belongsTo(KlasifikasiArsip::class);
    }

    public function statusSurat()
    {
        return $this->belongsTo(StatusSurat::class);
    }

    public function splaners()
    {
        return $this->hasMany(Splaner::class);
    }
}
