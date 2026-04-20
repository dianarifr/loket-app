<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loket extends Model
{
    protected $fillable = ['nama_loket', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function layanans(): BelongsToMany
    {
        return $this->belongsToMany(Layanan::class, 'layanan_loket');
    }

    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}
