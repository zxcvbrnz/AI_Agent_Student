<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'system_prompt',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
    ];
}