<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasAuditColumns;

class Notulensi extends Model
{
    use SoftDeletes, LogsActivity, HasAuditColumns;

    protected $fillable = [
        'tanggal',
        'tempat',
        'agenda',
        'pimpinan_rapat',
        'notulis_id',
        'peserta',
        'isi_notulensi',
        'file_path',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'peserta' => 'array',
        'approved_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['agenda', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function notulis()
    {
        return $this->belongsTo(User::class, 'notulis_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
