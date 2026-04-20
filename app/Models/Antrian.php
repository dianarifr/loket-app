<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Antrian extends Model
{
    protected $fillable = ['layanan_id', 'loket_id', 'nomor_urut', 'nomor_lengkap', 'status', 'called_at', 'finished_at', 'uuid'];

    protected $casts = [
        'called_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Antrian $antrian) {
            if (empty($antrian->uuid)) {
                $antrian->uuid = (string) Str::uuid();
            }
        });
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function loket(): BelongsTo
    {
        return $this->belongsTo(Loket::class);
    }

    public static function generateNomorAntrian(Layanan $layanan): array
    {
        $today = Carbon::today();

        $lastAntrian = static::where('layanan_id', $layanan->id)
            ->whereDate('created_at', $today)
            ->orderByDesc('nomor_urut')
            ->first();

        $nomorUrut = $lastAntrian ? $lastAntrian->nomor_urut + 1 : 1;
        $nomorLengkap = $layanan->prefix . '-' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        return [
            'nomor_urut' => $nomorUrut,
            'nomor_lengkap' => $nomorLengkap,
        ];
    }
}
