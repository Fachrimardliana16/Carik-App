<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasAuditColumns;

class SuratKeluar extends Model
{
    use SoftDeletes, LogsActivity, HasAuditColumns;

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'is_internal',
        'tujuan_user_id',
        'perihal',
        'sifat',
        'status',
        'isi_surat',
        'tembusan',
        'file_path',
        'penandatangan_id',
        'qr_code',
        'signature_hash',
        'signed_at',
        'template_id', // Added this line
        'klasifikasi_arsip_id',
        'status_surat_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'signed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor_surat', 'status', 'perihal'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function penandatangan()
    {
        return $this->belongsTo(User::class, 'penandatangan_id');
    }

    public function klasifikasiArsip()
    {
        return $this->belongsTo(KlasifikasiArsip::class);
    }

    public function statusSurat()
    {
        return $this->belongsTo(StatusSurat::class);
    }

    public function template()
    {
        return $this->belongsTo(TemplateSurat::class);
    }

    public function splaners()
    {
        return $this->hasMany(Splaner::class);
    }

    public function tujuanUser()
    {
        return $this->belongsTo(User::class, 'tujuan_user_id');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class);
    }
}
