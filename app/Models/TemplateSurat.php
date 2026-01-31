<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $fillable = ['name', 'content', 'description', 'kop_surat', 'logo_surat'];
}
