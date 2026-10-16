<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'prompt',
        'price',
        'color',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_levels')
            ->withPivot(['membership_type', 'membership_expires_at'])
            ->withTimestamps();
    }
}
