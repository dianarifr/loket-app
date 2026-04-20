<?php

namespace App\Events;

use App\Models\Antrian;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianCalled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $nomorLengkap;
    public string $namaLoket;
    public string $namaLayanan;

    public function __construct(Antrian $antrian)
    {
        $this->nomorLengkap = $antrian->nomor_lengkap;
        $this->namaLoket = $antrian->loket->nama_loket ?? '-';
        $this->namaLayanan = $antrian->layanan->nama_layanan ?? '-';
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrian.called';
    }
}
