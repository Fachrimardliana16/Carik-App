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
        'perihal',
        'sifat',
        'status',
        'isi_surat',
        'file_path',
        'penandatangan_id',
        'qr_code',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
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
}
