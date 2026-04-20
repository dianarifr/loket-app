<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Digital - {{ $antrian->nomor_lengkap }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-Bt5bcmEH.css') }}">
    <script type="module" src="{{ asset('build/assets/app-BUIVb32Z.js') }}"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }

        .ticket-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .ticket-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        }

        .ticket-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 2rem 1.5rem;
            text-align: center;
            color: #fff;
        }

        .ticket-header h1 {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .ticket-nomor {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .ticket-layanan {
            font-size: 1rem;
            opacity: 0.8;
            margin-top: 0.5rem;
        }

        .ticket-body {
            padding: 1.5rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .status-waiting { background: #e3f2fd; color: #1565c0; }
        .status-calling { background: #fff3cd; color: #f57f17; animation: blink 1s infinite; }
        .status-finished { background: #e8f5e9; color: #2e7d32; }
        .status-skip { background: #fce4ec; color: #c62828; }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child { border-bottom: none; }

        .info-label {
            font-size: 0.85rem;
            color: #888;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
        }

        .posisi-box {
            text-align: center;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1rem;
        }

        .posisi-label {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 0.25rem;
        }

        .posisi-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #667eea;
        }

        .posisi-desc {
            font-size: 0.8rem;
            color: #aaa;
            margin-top: 0.25rem;
        }

        .called-alert {
            display: none;
            text-align: center;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #1e293b;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            animation: blink 0.8s infinite;
        }

        .called-alert h3 {
            font-size: 1.3rem;
            margin-bottom: 0.25rem;
        }

        .called-alert p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .called-alert.show { display: block; }

        .ticket-footer {
            text-align: center;
            padding: 1rem 1.5rem 1.5rem;
            font-size: 0.8rem;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="ticket-wrapper">
        <div class="ticket-card">
            <div class="ticket-header">
                <h1>Tiket Antrian Digital</h1>
                <div class="ticket-nomor" id="nomorLengkap">{{ $antrian->nomor_lengkap }}</div>
                <div class="ticket-layanan">{{ $antrian->layanan->nama_layanan }}</div>
            </div>

            <div class="ticket-body">
                <div style="text-align: center;">
                    <span class="status-badge status-{{ $antrian->status }}" id="statusBadge">
                        <span id="statusText">{{ ucfirst($antrian->status) }}</span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Layanan</span>
                    <span class="info-value">{{ $antrian->layanan->nama_layanan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Diambil pada</span>
                    <span class="info-value">{{ $antrian->created_at->format('H:i:s') }}</span>
                </div>
                <div class="info-row" id="loketRow" style="{{ $antrian->loket ? '' : 'display:none' }}">
                    <span class="info-label">Loket</span>
                    <span class="info-value" id="loketName">{{ $antrian->loket?->nama_loket ?? '-' }}</span>
                </div>

                <div class="posisi-box" id="posisiBox">
                    <div class="posisi-label">Posisi Antrian</div>
                    <div class="posisi-number" id="posisiNumber">-</div>
                    <div class="posisi-desc">orang di depan Anda</div>
                </div>

                <div class="called-alert" id="calledAlert">
                    <h3>🔔 Anda Dipanggil!</h3>
                    <p id="calledLoketText">Silakan menuju loket</p>
                </div>
            </div>

            <div class="ticket-footer">
                Halaman ini diperbarui secara otomatis
            </div>
        </div>
    </div>

    <script type="module">
        const uuid = "{{ $antrian->uuid }}";

        function waitForEcho(callback) {
            if (window.Echo) callback();
            else setTimeout(() => waitForEcho(callback), 100);
        }

        async function refreshStatus() {
            try {
                const res = await fetch(`/api/tiket/${uuid}/status`);
                const data = await res.json();

                // Update status badge
                const badge = document.getElementById('statusBadge');
                badge.className = `status-badge status-${data.status}`;
                document.getElementById('statusText').textContent =
                    data.status === 'waiting' ? 'Menunggu' :
                    data.status === 'calling' ? 'Dipanggil' :
                    data.status === 'finished' ? 'Selesai' : 'Dilewati';

                // Posisi
                const posisiBox = document.getElementById('posisiBox');
                const calledAlert = document.getElementById('calledAlert');

                if (data.status === 'waiting') {
                    posisiBox.style.display = 'block';
                    calledAlert.classList.remove('show');
                    document.getElementById('posisiNumber').textContent = data.posisi;
                } else if (data.status === 'calling') {
                    posisiBox.style.display = 'none';
                    calledAlert.classList.add('show');
                    document.getElementById('calledLoketText').textContent = `Silakan menuju ${data.loket || 'loket'}`;
                    // Vibrate if available
                    if (navigator.vibrate) navigator.vibrate([200, 100, 200, 100, 200]);
                } else {
                    posisiBox.style.display = 'none';
                    calledAlert.classList.remove('show');
                }

                // Loket
                if (data.loket) {
                    document.getElementById('loketRow').style.display = '';
                    document.getElementById('loketName').textContent = data.loket;
                }
            } catch (e) {
                console.error('Failed to fetch status:', e);
            }
        }

        // Initial load
        refreshStatus();

        // Real-time updates
        waitForEcho(() => {
            window.Echo.channel('antrian')
                .listen('.antrian.called', () => refreshStatus())
                .listen('.antrian.updated', () => refreshStatus());
        });
    </script>
</body>
</html>
