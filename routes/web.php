<?php

use App\Http\Controllers\OperatorController;
use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/display', function () {
    $youtubeUrl = Setting::get('youtube_url', '');
    $runningText = Setting::get('running_text', 'Selamat datang di layanan kami. Silakan ambil nomor antrian.');

    // Convert YouTube URL to embed URL
    $youtubeEmbedUrl = '';
    if ($youtubeUrl) {
        $videoId = null;
        if (preg_match('/[?&]v=([^&]+)/', $youtubeUrl, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^?]+)/', $youtubeUrl, $matches)) {
            $videoId = $matches[1];
        }
        if ($videoId) {
            $youtubeEmbedUrl = "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&playlist={$videoId}";
        }
    }

    return view('display', compact('youtubeUrl', 'youtubeEmbedUrl', 'runningText'));
})->name('display');

Route::get('/ambil-antrian', function () {
    $layanans = Layanan::where('is_active', true)->get();

    $antrianTerakhir = Antrian::whereDate('created_at', today())
        ->orderByDesc('created_at')
        ->take(5)
        ->get();

    $sedangDipanggil = Antrian::where('status', 'calling')
        ->whereDate('created_at', today())
        ->with(['layanan', 'loket'])
        ->orderByDesc('called_at')
        ->take(5)
        ->get();

    return view('ambil-antrian', compact('layanans', 'antrianTerakhir', 'sedangDipanggil'));
})->name('ambil-antrian');

Route::post('/ambil-antrian', function () {
    $layanan = Layanan::findOrFail(request('layanan_id'));
    $nomor = Antrian::generateNomorAntrian($layanan);

    $antrian = Antrian::create([
        'layanan_id' => $layanan->id,
        'nomor_urut' => $nomor['nomor_urut'],
        'nomor_lengkap' => $nomor['nomor_lengkap'],
        'status' => 'waiting',
    ]);

    return redirect()->route('ambil-antrian')->with([
        'success' => "Nomor antrian Anda: {$antrian->nomor_lengkap}",
        'antrian_uuid' => $antrian->uuid,
        'antrian_nomor' => $antrian->nomor_lengkap,
    ]);
})->name('ambil-antrian.store');

Route::get('/tiket/{uuid}', function (string $uuid) {
    $antrian = Antrian::where('uuid', $uuid)->with(['layanan', 'loket'])->firstOrFail();
    return view('tiket', compact('antrian'));
})->name('tiket');

// API for tiket status check
Route::get('/api/tiket/{uuid}/status', function (string $uuid) {
    $antrian = Antrian::where('uuid', $uuid)->with(['layanan', 'loket'])->firstOrFail();
    $posisi = 0;
    if ($antrian->status === 'waiting') {
        $posisi = Antrian::where('layanan_id', $antrian->layanan_id)
            ->where('status', 'waiting')
            ->whereDate('created_at', today())
            ->where('nomor_urut', '<', $antrian->nomor_urut)
            ->count();
    }
    return response()->json([
        'nomor_lengkap' => $antrian->nomor_lengkap,
        'status' => $antrian->status,
        'layanan' => $antrian->layanan->nama_layanan,
        'loket' => $antrian->loket?->nama_loket,
        'posisi' => $posisi,
        'called_at' => $antrian->called_at?->format('H:i:s'),
    ]);
});

Route::get('/operator', function () {
    return view('operator');
})->name('operator');

// TTS proxy endpoint
Route::get('/tts', function () {
    $text = request('text', '');
    if (empty($text) || strlen($text) > 200) {
        abort(400);
    }

    $cacheKey = 'tts_' . md5($text);
    $cachePath = storage_path('app/tts/' . $cacheKey . '.mp3');

    if (!file_exists($cachePath)) {
        if (!is_dir(storage_path('app/tts'))) {
            mkdir(storage_path('app/tts'), 0755, true);
        }

        $url = 'https://translate.google.com/translate_tts?ie=UTF-8&tl=id&client=tw-ob&q=' . urlencode($text);
        $opts = [
            'http' => [
                'header' => "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36\r\n",
                'timeout' => 10,
            ]
        ];
        $ctx = stream_context_create($opts);
        $audio = @file_get_contents($url, false, $ctx);

        if (empty($audio)) {
            abort(503, 'TTS service unavailable');
        }

        file_put_contents($cachePath, $audio);
    }

    return response()->file($cachePath, [
        'Content-Type' => 'audio/mpeg',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('tts');

// Operator API
Route::prefix('operator-api')->group(function () {
    Route::get('/lokets', [OperatorController::class, 'getLokets']);
    Route::post('/lokets/{loket}/toggle', [OperatorController::class, 'toggleLoket']);
    Route::get('/lokets/{loket}/antrian', [OperatorController::class, 'getAntrian']);
    Route::post('/lokets/{loket}/panggil', [OperatorController::class, 'panggilBerikutnya']);
    Route::post('/lokets/{loket}/panggil-ulang', [OperatorController::class, 'panggilUlang']);
    Route::post('/lokets/{loket}/skip', [OperatorController::class, 'skip']);
    Route::post('/lokets/{loket}/selesai', [OperatorController::class, 'selesai']);
    Route::post('/lokets/{loket}/panggil-nomor/{antrian}', [OperatorController::class, 'panggilNomor']);
    Route::get('/antrian-info', [OperatorController::class, 'antrianInfo']);
});
