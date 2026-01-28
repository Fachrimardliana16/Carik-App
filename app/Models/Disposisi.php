<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasAuditColumns;

class Disposisi extends Model
{
    use SoftDeletes, LogsActivity, HasAuditColumns;

    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'kepada_user_id',
        'instruksi',
        'prioritas',
        'status',
        'batas_waktu',
        'dibaca_pada',
        'selesai_pada',
        'catatan_penyelesaian',
    ];

    protected $casts = [
        'batas_waktu' => 'date',
        'dibaca_pada' => 'datetime',
        'selesai_pada' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'instruksi'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function kepadaUser()
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }
}
