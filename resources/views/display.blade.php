<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-Bt5bcmEH.css') }}">
    <script type="module" src="{{ asset('build/assets/app-BUIVb32Z.js') }}"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #fff; height: 100vh; overflow: hidden; }

        .display-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr auto;
            height: 100vh;
        }

        /* Left Panel - Calling Info */
        .left-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            position: relative;
        }

        .clock {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            font-size: 1.4rem;
            font-weight: 300;
            opacity: 0.7;
            font-variant-numeric: tabular-nums;
        }

        .date-display {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1rem;
            font-weight: 300;
            opacity: 0.6;
            text-align: right;
        }

        .calling-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .calling-label {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 4px;
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .calling-number {
            font-size: 7rem;
            font-weight: 800;
            color: #fbbf24;
            text-shadow: 0 0 40px rgba(251,191,36,0.4);
            line-height: 1;
            transition: all 0.3s ease;
        }

        .calling-loket {
            font-size: 2.2rem;
            margin-top: 1rem;
            color: #38bdf8;
            font-weight: 600;
        }

        .calling-layanan {
            font-size: 1.1rem;
            margin-top: 0.5rem;
            opacity: 0.6;
        }

        /* History */
        .history-section {
            width: 100%;
            max-width: 500px;
        }

        .history-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.4;
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .history-grid {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .history-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            text-align: center;
            min-width: 100px;
        }

        .history-item .number {
            font-size: 1.2rem;
            font-weight: 600;
            color: #86efac;
        }

        .history-item .loket {
            font-size: 0.7rem;
            opacity: 0.5;
            margin-top: 0.25rem;
        }

        /* Right Panel - YouTube/Media */
        .right-panel {
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .right-panel iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .no-video {
            text-align: center;
            opacity: 0.3;
        }

        .no-video svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }

        /* Running Text */
        .running-text-bar {
            grid-column: 1 / -1;
            background: linear-gradient(90deg, #1e40af, #7c3aed);
            padding: 0.75rem 0;
            overflow: hidden;
            position: relative;
        }

        .running-text {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        @keyframes marquee {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(10px);
        }

        .overlay.hidden { display: none; }

        .overlay-content {
            text-align: center;
        }

        .overlay-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 300;
        }

        .overlay-content p {
            font-size: 1.1rem;
            opacity: 0.6;
            margin-bottom: 2rem;
        }

        .btn-activate {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #1e293b;
            border: none;
            padding: 1.2rem 3.5rem;
            font-size: 1.3rem;
            font-weight: 700;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(251,191,36,0.3);
        }

        .btn-activate:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(251,191,36,0.5);
        }

        /* Animations */
        .pulse { animation: pulse 0.8s ease-in-out; }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        .flash {
            animation: flash 0.5s ease-in-out 3;
        }
        @keyframes flash {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>
<body>
    <!-- Overlay untuk izin suara -->
    <div class="overlay" id="soundOverlay">
        <div class="overlay-content">
            <h2>🔊 Sistem Antrian</h2>
            <p>Klik tombol untuk mengaktifkan suara dan tampilan</p>
            <button class="btn-activate" id="btnActivateSound">Aktifkan Display</button>
        </div>
    </div>

    <div class="display-grid">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="clock" id="clock">00:00:00</div>
            <div class="date-display" id="dateDisplay"></div>

            <div class="calling-section">
                <div class="calling-label">Nomor Antrian Dipanggil</div>
                <div class="calling-number" id="callingNumber">---</div>
                <div class="calling-loket" id="callingLoket">Menunggu panggilan...</div>
                <div class="calling-layanan" id="callingLayanan"></div>
            </div>

            <div class="history-section">
                <div class="history-title">Riwayat Panggilan</div>
                <div class="history-grid" id="historyContainer"></div>
            </div>
        </div>

        <!-- Right Panel - YouTube -->
        <div class="right-panel" id="mediaPanel">
            @if($youtubeUrl)
                <iframe src="{{ $youtubeEmbedUrl }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            @else
                <div class="no-video">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p>Video tidak tersedia</p>
                </div>
            @endif
        </div>

        <!-- Running Text -->
        <div class="running-text-bar">
            <span class="running-text" id="runningText">{{ $runningText }}</span>
        </div>
    </div>

    <!-- Ding Dong Audio -->
    <audio id="dingDong" src="{{ asset('audio/ding-dong.wav') }}" preload="auto"></audio>

    <script type="module">
        function waitForEcho(callback) {
            if (window.Echo) {
                callback();
            } else {
                setTimeout(() => waitForEcho(callback), 100);
            }
        }

        let soundEnabled = false;
        const speechQueue = [];
        let isSpeaking = false;
        const dingDongAudio = document.getElementById('dingDong');

        // Clock
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const date = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('clock').textContent = time;
            document.getElementById('dateDisplay').textContent = date;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Activate sound
        document.getElementById('btnActivateSound').addEventListener('click', () => {
            soundEnabled = true;
            document.getElementById('soundOverlay').classList.add('hidden');
            dingDongAudio.volume = 0;
            dingDongAudio.play().then(() => {
                dingDongAudio.pause();
                dingDongAudio.currentTime = 0;
                dingDongAudio.volume = 1;
            }).catch(() => {});
        });

        // Server-side TTS (Google voice, cached, reliable)
        const ttsAudio = new Audio();

        function getTTSUrl(text) {
            return `/tts?text=${encodeURIComponent(text)}`;
        }

        function processSpeechQueue() {
            if (isSpeaking || speechQueue.length === 0) return;
            isSpeaking = true;
            const text = speechQueue.shift();
            ttsAudio.src = getTTSUrl(text);
            ttsAudio.load();
            ttsAudio.play().catch(() => {
                isSpeaking = false;
                processSpeechQueue();
            });
        }

        ttsAudio.addEventListener('ended', () => {
            isSpeaking = false;
            // Small delay between repeats
            setTimeout(() => processSpeechQueue(), 300);
        });

        ttsAudio.addEventListener('error', () => {
            isSpeaking = false;
            processSpeechQueue();
        });

        function speak(text) {
            if (!soundEnabled) return;
            speechQueue.push(text);
            processSpeechQueue();
        }

        function dingDongThenSpeak(text) {
            if (!soundEnabled) return;
            dingDongAudio.currentTime = 0;
            dingDongAudio.play().then(() => {
                setTimeout(() => {
                    speak(text);
                    // Repeat once after first finishes (handled by queue)
                    speechQueue.push(text);
                }, 1200);
            }).catch(() => {
                speak(text);
                speechQueue.push(text);
            });
        }

        // History
        const history = [];

        function updateDisplay(data) {
            const numberEl = document.getElementById('callingNumber');
            const loketEl = document.getElementById('callingLoket');
            const layananEl = document.getElementById('callingLayanan');

            numberEl.textContent = data.nomorLengkap;
            loketEl.textContent = data.namaLoket;
            layananEl.textContent = data.namaLayanan;

            numberEl.classList.remove('pulse', 'flash');
            void numberEl.offsetWidth;
            numberEl.classList.add('pulse');
            numberEl.classList.add('flash');

            history.unshift(data);
            if (history.length > 5) history.pop();
            renderHistory();

            const nomor = data.nomorLengkap.replace('-', ' ');
            const speechText = `Nomor antrian ${nomor}, silahkan menuju ${data.namaLoket}`;
            dingDongThenSpeak(speechText);
        }

        function renderHistory() {
            const container = document.getElementById('historyContainer');
            container.innerHTML = history.map(item => `
                <div class="history-item">
                    <div class="number">${item.nomorLengkap}</div>
                    <div class="loket">${item.namaLoket}</div>
                </div>
            `).join('');
        }

        waitForEcho(() => {
            window.Echo.channel('antrian')
                .listen('.antrian.called', (data) => {
                    updateDisplay(data);
                });
        });
    </script>
</body>
</html>
