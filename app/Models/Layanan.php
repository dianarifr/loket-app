<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $fillable = ['nama_layanan', 'prefix', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lokets(): BelongsToMany
    {
        return $this->belongsToMany(Loket::class, 'layanan_loket');
    }

    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}
