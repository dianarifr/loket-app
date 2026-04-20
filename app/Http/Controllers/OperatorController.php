<?php

namespace App\Http\Controllers;

use App\Events\AntrianCalled;
use App\Events\AntrianUpdated;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function getLokets()
    {
        $lokets = Loket::with('layanans')->get();
        return response()->json($lokets);
    }

    public function toggleLoket(Loket $loket)
    {
        $loket->update(['is_active' => !$loket->is_active]);
        return response()->json(['success' => true, 'is_active' => $loket->is_active]);
    }

    public function getAntrian(Loket $loket)
    {
        $layananIds = $loket->layanans->pluck('id')->toArray();

        $waiting = Antrian::whereIn('layanan_id', $layananIds)
            ->where('status', 'waiting')
            ->whereDate('created_at', today())
            ->with('layanan')
            ->orderBy('nomor_urut')
            ->get();

        $current = Antrian::where('loket_id', $loket->id)
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->with('layanan')
            ->first();

        $pending = Antrian::whereIn('layanan_id', $layananIds)
            ->whereIn('status', ['skip', 'calling'])
            ->whereDate('created_at', today())
            ->where(function ($q) use ($loket) {
                $q->where('loket_id', $loket->id)->orWhereNull('loket_id');
            })
            ->with('layanan')
            ->orderBy('created_at', 'asc')
            ->get();

        $finished = Antrian::whereIn('layanan_id', $layananIds)
            ->where('status', 'finished')
            ->whereDate('created_at', today())
            ->with('layanan')
            ->orderByDesc('called_at')
            ->take(10)
            ->get();

        return response()->json([
            'waiting' => $waiting,
            'current' => $current,
            'pending' => $pending,
            'finished' => $finished,
            'loket' => $loket->load('layanans'),
        ]);
    }

    public function panggilBerikutnya(Loket $loket)
    {
        // Cek apakah masih ada yang sedang dipanggil
        $masihCalling = Antrian::where('loket_id', $loket->id)
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->exists();

        if ($masihCalling) {
            return response()->json(['success' => false, 'message' => 'Selesaikan antrian yang sedang dipanggil terlebih dahulu.']);
        }

        $layananIds = $loket->layanans->pluck('id')->toArray();

        $antrian = Antrian::where('status', 'waiting')
            ->whereIn('layanan_id', $layananIds)
            ->whereDate('created_at', today())
            ->orderBy('created_at')
            ->orderBy('nomor_urut')
            ->first();

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Tidak ada antrian menunggu.']);
        }

        $antrian->update([
            'status' => 'calling',
            'loket_id' => $loket->id,
            'called_at' => now(),
        ]);

        $antrian->load(['layanan', 'loket']);
        event(new AntrianCalled($antrian));

        return response()->json(['success' => true, 'antrian' => $antrian]);
    }

    public function panggilUlang(Loket $loket)
    {
        $antrian = Antrian::where('loket_id', $loket->id)
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->with(['layanan', 'loket'])
            ->first();

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Tidak ada antrian yang dipanggil.']);
        }

        $antrian->update(['called_at' => now()]);
        event(new AntrianCalled($antrian));

        return response()->json(['success' => true, 'antrian' => $antrian]);
    }

    public function skip(Loket $loket)
    {
        $antrian = Antrian::where('loket_id', $loket->id)
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->first();

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Tidak ada antrian yang dipanggil.']);
        }

        $antrian->update(['status' => 'skip']);

        event(new AntrianUpdated());

        return response()->json(['success' => true]);
    }

    public function selesai(Loket $loket)
    {
        Antrian::where('loket_id', $loket->id)
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->update(['status' => 'finished', 'finished_at' => now()]);

        event(new AntrianUpdated());

        return response()->json(['success' => true]);
    }

    public function panggilNomor(Loket $loket, Antrian $antrian)
    {
        // Cek apakah masih ada yang sedang dipanggil
        $masihCalling = Antrian::where('loket_id', $loket->id)
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->exists();

        if ($masihCalling) {
            return response()->json(['success' => false, 'message' => 'Selesaikan antrian yang sedang dipanggil terlebih dahulu.']);
        }

        $antrian->update([
            'status' => 'calling',
            'loket_id' => $loket->id,
            'called_at' => now(),
        ]);

        $antrian->load(['layanan', 'loket']);
        event(new AntrianCalled($antrian));

        return response()->json(['success' => true, 'antrian' => $antrian]);
    }

    public function antrianInfo()
    {
        $calling = Antrian::where('status', 'calling')
            ->whereDate('created_at', today())
            ->with(['loket'])
            ->orderByDesc('called_at')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'nomor_lengkap' => $a->nomor_lengkap,
                'loket_nama' => $a->loket->nama_loket ?? '-',
            ]);

        $latest = Antrian::whereDate('created_at', today())
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'nomor_lengkap' => $a->nomor_lengkap,
                'status' => $a->status,
            ]);

        return response()->json(['calling' => $calling, 'latest' => $latest]);
    }
}
