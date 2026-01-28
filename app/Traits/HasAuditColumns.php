<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasAuditColumns
{
    public static function bootHasAuditColumns()
    {
        static::creating(function ($model) {
            if (!$model->isDirty('created_by') && Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (!$model->isDirty('updated_by') && Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            static::deleting(function ($model) {
                if (!$model->isDirty('deleted_by') && Auth::check()) {
                    $model->deleted_by = Auth::id();
                    $model->save();
                }
            });
        }
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }
}
