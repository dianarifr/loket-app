<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-Bt5bcmEH.css') }}">
    <script type="module" src="{{ asset('build/assets/app-BUIVb32Z.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem; }
        .page-header { text-align: center; color: #fff; margin-bottom: 2rem; }
        .page-header h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; }
        .page-header p { font-size: 1rem; opacity: 0.8; }

        .success-banner { max-width: 600px; margin: 0 auto 2rem; background: #fff; border-left: 5px solid #4caf50; border-radius: 12px; padding: 1.5rem 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; }
        .success-banner .ticket-number { font-size: 2.5rem; font-weight: 800; color: #1e3a5f; margin: 0.5rem 0; }
        .success-banner .ticket-label { font-size: 0.9rem; color: #666; text-transform: uppercase; letter-spacing: 1px; }

        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; max-width: 1000px; margin: 0 auto; }

        .card { background: #fff; border-radius: 16px; padding: 2rem; text-align: center; box-shadow: 0 8px 30px rgba(0,0,0,0.12); transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; position: relative; overflow: hidden; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.18); }
        .card:active { transform: translateY(0); }
        .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #667eea, #764ba2); }

        .card .prefix { display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; }
        .card .nama { font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 0.5rem; }
        .card .desc { font-size: 0.85rem; color: #999; margin-bottom: 1.2rem; }
        .card .btn-ambil { display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 0.7rem 2rem; border-radius: 25px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .card .btn-ambil:hover { opacity: 0.9; }

        .info-section { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; max-width: 1000px; margin: 2rem auto 0; }
        .info-panel { background: rgba(255,255,255,0.95); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .info-panel h3 { font-size: 1rem; color: #333; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #eee; }
        .info-item { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.8rem; margin-bottom: 0.4rem; border-radius: 8px; background: #f8f9fa; }
        .info-item.calling { background: #fff3cd; animation: blink 1.5s infinite; }
        .info-number { font-weight: 700; font-size: 1.1rem; color: #1e3a5f; }
        .info-detail { font-size: 0.85rem; color: #555; font-weight: 500; }
        .info-status { font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 600; text-transform: uppercase; }
        .status-waiting { background: #e3f2fd; color: #1565c0; }
        .status-calling { background: #fff3cd; color: #f57f17; }
        .status-finished { background: #e8f5e9; color: #2e7d32; }
        .status-skip { background: #fce4ec; color: #c62828; }
        .info-empty { text-align: center; color: #999; font-size: 0.9rem; padding: 1rem; }
        @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0.7; } }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Ambil Nomor Antrian</h1>
        <p>Pilih jenis layanan yang Anda butuhkan</p>
    </div>

    @if(session('success'))
        <div class="success-banner">
            <div class="ticket-label">Nomor Antrian Anda</div>
            <div class="ticket-number">{{ session('antrian_nomor') }}</div>
            <div style="margin: 1rem auto; width: 180px;" id="qrCode"></div>
            <a href="{{ route('tiket', session('antrian_uuid')) }}" target="_blank" style="display:inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; padding: 0.6rem 1.5rem; border-radius: 20px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                Lihat Tiket Digital
            </a>
            <div class="ticket-label" style="margin-top: 0.75rem;">Scan QR atau klik tombol untuk tiket digital</div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var tiketUrl = "{{ route('tiket', session('antrian_uuid')) }}";
                new QRCode(document.getElementById('qrCode'), {
                    text: tiketUrl,
                    width: 180,
                    height: 180,
                    colorDark: '#1e3a5f',
                    colorLight: '#ffffff'
                });
            });
        </script>
    @endif

    <div class="grid">
        @foreach($layanans as $layanan)
            <form method="POST" action="{{ route('ambil-antrian.store') }}" class="card">
                @csrf
                <div class="prefix">{{ $layanan->prefix }}</div>
                <div class="nama">{{ $layanan->nama_layanan }}</div>
                <div class="desc">Tekan untuk mengambil antrian</div>
                <button type="submit" name="layanan_id" value="{{ $layanan->id }}" class="btn-ambil">
                    Ambil Antrian
                </button>
            </form>
        @endforeach
    </div>

    {{-- Info Panel --}}
    <div class="info-section">
        <div class="info-panel">
            <h3>🔔 Sedang Dipanggil</h3>
            <div id="sedangDipanggil">
                @forelse($sedangDipanggil as $item)
                    <div class="info-item calling">
                        <span class="info-number">{{ $item->nomor_lengkap }}</span>
                        <span class="info-detail">→ {{ $item->loket->nama_loket ?? '-' }}</span>
                    </div>
                @empty
                    <div class="info-empty">Belum ada panggilan</div>
                @endforelse
            </div>
        </div>

        <div class="info-panel">
            <h3>📋 Antrian Terakhir</h3>
            <div id="antrianTerakhir">
                @forelse($antrianTerakhir as $item)
                    <div class="info-item">
                        <span class="info-number">{{ $item->nomor_lengkap }}</span>
                        <span class="info-status status-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                    </div>
                @empty
                    <div class="info-empty">Belum ada antrian hari ini</div>
                @endforelse
            </div>
        </div>
    </div>

    <script type="module">
        async function refreshInfo() {
            const res = await fetch('/operator-api/antrian-info');
            const data = await res.json();

            // Update sedang dipanggil
            const callingEl = document.getElementById('sedangDipanggil');
            if (data.calling.length) {
                callingEl.innerHTML = data.calling.map(item => `
                    <div class="info-item calling">
                        <span class="info-number">${item.nomor_lengkap}</span>
                        <span class="info-detail">→ ${item.loket_nama}</span>
                    </div>
                `).join('');
            } else {
                callingEl.innerHTML = '<div class="info-empty">Belum ada panggilan</div>';
            }

            // Update antrian terakhir
            const terakhirEl = document.getElementById('antrianTerakhir');
            if (data.latest.length) {
                terakhirEl.innerHTML = data.latest.map(item => `
                    <div class="info-item">
                        <span class="info-number">${item.nomor_lengkap}</span>
                        <span class="info-status status-${item.status}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span>
                    </div>
                `).join('');
            } else {
                terakhirEl.innerHTML = '<div class="info-empty">Belum ada antrian hari ini</div>';
            }
        }

        function initEcho() {
            if (window.Echo) {
                window.Echo.channel('antrian')
                    .listen('.antrian.called', () => refreshInfo())
                    .listen('.antrian.updated', () => refreshInfo());
            } else {
                setTimeout(initEcho, 200);
            }
        }
        initEcho();
    </script>
</body>
</html>
