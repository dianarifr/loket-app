<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Operator Panel</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-Bt5bcmEH.css') }}">
    <script type="module" src="{{ asset('build/assets/app-BUIVb32Z.js') }}"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: var(--gray-100); min-height: 100vh; color: var(--gray-800); }

        /* Header */
        .topbar { background: #fff; border-bottom: 1px solid var(--gray-200); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .topbar h1 { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); }
        .topbar .back-btn { display: none; background: var(--gray-100); border: none; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; color: var(--gray-600); transition: all .15s; }
        .topbar .back-btn:hover { background: var(--gray-200); }
        .topbar .back-btn.visible { display: inline-flex; align-items: center; gap: 0.4rem; }

        /* Loket Grid */
        .loket-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .loket-card { background: #fff; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); transition: transform .2s, box-shadow .2s; cursor: pointer; position: relative; }
        .loket-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .loket-card .status-strip { height: 4px; width: 100%; }
        .loket-card .status-strip.active { background: var(--success); }
        .loket-card .status-strip.inactive { background: var(--danger); }
        .loket-card .card-body { padding: 1.5rem; }
        .loket-card .card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .loket-card .card-title { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); }
        .loket-card .badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .loket-card .services { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1.25rem; }
        .loket-card .service-tag { background: #eef2ff; color: #4338ca; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; }
        .loket-card .card-actions { display: flex; gap: 0.5rem; }
        .btn { border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-danger-outline { background: transparent; border: 1.5px solid var(--danger); color: var(--danger); }
        .btn-danger-outline:hover { background: #fef2f2; }
        .btn-success-outline { background: transparent; border: 1.5px solid var(--success); color: var(--success); }
        .btn-success-outline:hover { background: #ecfdf5; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.75rem; }

        /* Operator Panel */
        .operator-panel { display: none; padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .operator-panel.active { display: block; }
        .op-grid { display: grid; grid-template-columns: 350px 1fr; gap: 1.5rem; }
        @media (max-width: 900px) { .op-grid { grid-template-columns: 1fr; } }

        /* Left: Call Panel */
        .call-panel { position: sticky; top: 80px; }
        .current-call { background: #fff; border-radius: var(--radius); padding: 2rem; text-align: center; box-shadow: var(--shadow); margin-bottom: 1rem; border: 2px solid var(--gray-200); }
        .current-call.has-call { border-color: var(--primary); background: linear-gradient(135deg, #eef2ff 0%, #fff 100%); }
        .current-call .label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gray-400); font-weight: 600; margin-bottom: 0.5rem; }
        .current-call .number { font-size: 3.5rem; font-weight: 900; color: var(--primary); line-height: 1; margin-bottom: 0.5rem; }
        .current-call .number.empty { color: var(--gray-300); font-size: 2.5rem; }
        .current-call .service-name { font-size: 0.9rem; color: var(--gray-500); }

        .action-buttons { display: flex; flex-direction: column; gap: 0.5rem; }
        .btn-call { background: var(--primary); color: #fff; width: 100%; padding: 0.9rem; font-size: 1rem; border-radius: 10px; font-weight: 700; }
        .btn-call:hover { background: var(--primary-dark); transform: scale(1.01); }
        .btn-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
        .btn-recall { background: #dbeafe; color: #1d4ed8; }
        .btn-recall:hover { background: #bfdbfe; }
        .btn-skip { background: #fef3c7; color: #92400e; }
        .btn-skip:hover { background: #fde68a; }
        .btn-finish { background: #d1fae5; color: #065f46; width: 100%; }
        .btn-finish:hover { background: #a7f3d0; }

        /* Right: Queue List */
        .queue-section { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .queue-tabs { display: flex; border-bottom: 1px solid var(--gray-200); background: var(--gray-50); }
        .queue-tab { flex: 1; padding: 0.8rem; text-align: center; font-size: 0.8rem; font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; color: var(--gray-500); transition: all .15s; }
        .queue-tab.active { color: var(--primary); border-bottom-color: var(--primary); background: #fff; }
        .queue-tab .count { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; border-radius: 10px; background: var(--gray-200); color: var(--gray-600); font-size: 0.7rem; margin-left: 0.3rem; }
        .queue-tab.active .count { background: #eef2ff; color: var(--primary); }

        .queue-list { max-height: 500px; overflow-y: auto; }
        .queue-item { display: flex; align-items: center; justify-content: space-between; padding: 0.8rem 1.2rem; border-bottom: 1px solid var(--gray-100); transition: background .1s; }
        .queue-item:hover { background: var(--gray-50); }
        .queue-item .left { display: flex; align-items: center; gap: 0.8rem; }
        .queue-item .q-number { font-size: 1.1rem; font-weight: 700; color: var(--gray-800); min-width: 70px; }
        .queue-item .q-service { font-size: 0.8rem; color: var(--gray-500); }
        .queue-item .q-badge { padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 600; }
        .q-badge-waiting { background: #dbeafe; color: #1d4ed8; }
        .q-badge-calling { background: #fef3c7; color: #92400e; }
        .q-badge-skip { background: #fee2e2; color: #991b1b; }
        .q-badge-finished { background: #d1fae5; color: #065f46; }
        .queue-item .q-action { opacity: 0; transition: opacity .15s; }
        .queue-item:hover .q-action { opacity: 1; }
        .queue-empty { padding: 3rem; text-align: center; color: var(--gray-400); }
        .queue-empty svg { width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.3; }

        /* Loading */
        .loading { display: flex; align-items: center; justify-content: center; padding: 3rem; }
        .spinner { width: 32px; height: 32px; border: 3px solid var(--gray-200); border-top-color: var(--primary); border-radius: 50%; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Toast */
        .toast { position: fixed; bottom: 2rem; right: 2rem; background: var(--gray-800); color: #fff; padding: 0.8rem 1.5rem; border-radius: 10px; font-size: 0.9rem; font-weight: 500; z-index: 9999; opacity: 0; transform: translateY(10px); transition: all .3s; }
        .toast.show { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:1rem;">
            <button class="back-btn" id="backBtn" onclick="showLoketGrid()">← Kembali</button>
            <h1 id="pageTitle">Pilih Loket</h1>
        </div>
        <a href="/admin" style="font-size:0.85rem;color:var(--gray-500);text-decoration:none;">← Admin Panel</a>
    </div>

    <!-- Loket Grid -->
    <div class="loket-grid" id="loketGrid">
        <div class="loading"><div class="spinner"></div></div>
    </div>

    <!-- Operator Panel -->
    <div class="operator-panel" id="operatorPanel">
        <div class="op-grid">
            <div class="call-panel">
                <div class="current-call" id="currentCall">
                    <div class="label">Sedang Dipanggil</div>
                    <div class="number empty" id="currentNumber">---</div>
                    <div class="service-name" id="currentService"></div>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-call" id="btnPanggil" onclick="panggilBerikutnya()">📢 Panggil Berikutnya</button>
                    <div class="btn-row" id="activeActions" style="display:none;">
                        <button class="btn btn-recall" onclick="panggilUlang()">🔁 Panggil Ulang</button>
                        <button class="btn btn-skip" onclick="skipAntrian()">⏭ Skip</button>
                    </div>
                    <button class="btn btn-finish" id="finishBtn" style="display:none;" onclick="selesai()">✅ Selesai (Tanpa Panggil)</button>
                </div>
            </div>

            <div class="queue-section">
                <div class="queue-tabs">
                    <div class="queue-tab active" data-tab="waiting" onclick="switchTab('waiting')">Menunggu <span class="count" id="countWaiting">0</span></div>
                    <div class="queue-tab" data-tab="pending" onclick="switchTab('pending')">Skip/Calling <span class="count" id="countPending">0</span></div>
                    <div class="queue-tab" data-tab="finished" onclick="switchTab('finished')">Selesai <span class="count" id="countFinished">0</span></div>
                </div>
                <div class="queue-list" id="queueList">
                    <div class="loading"><div class="spinner"></div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let selectedLoket = null;
        let currentTab = 'waiting';
        let queueData = { waiting: [], pending: [], finished: [] };

        // Fetch helpers
        async function api(url, method = 'GET') {
            const opts = { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } };
            const res = await fetch(url, opts);
            return res.json();
        }

        function toast(msg) {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.classList.add('show');
            setTimeout(() => el.classList.remove('show'), 3000);
        }

        // Load Lokets
        async function loadLokets() {
            const data = await api('/operator-api/lokets');
            const grid = document.getElementById('loketGrid');
            if (!data.length) {
                grid.innerHTML = '<div class="queue-empty"><p>Belum ada loket.</p></div>';
                return;
            }
            grid.innerHTML = data.map(loket => `
                <div class="loket-card">
                    <div class="status-strip ${loket.is_active ? 'active' : 'inactive'}"></div>
                    <div class="card-body">
                        <div class="card-header">
                            <div class="card-title">${loket.nama_loket}</div>
                            <span class="badge ${loket.is_active ? 'badge-active' : 'badge-inactive'}">${loket.is_active ? 'Aktif' : 'Tutup'}</span>
                        </div>
                        <div class="services">
                            ${loket.layanans.map(l => `<span class="service-tag">${l.prefix} - ${l.nama_layanan}</span>`).join('')}
                        </div>
                        <div class="card-actions">
                            ${loket.is_active ? `<button class="btn btn-primary" onclick="event.stopPropagation();enterLoket(${loket.id})">📢 Masuk Loket</button>` : ''}
                            <button class="btn ${loket.is_active ? 'btn-danger-outline' : 'btn-success-outline'} btn-sm" onclick="event.stopPropagation();toggleLoket(${loket.id})">
                                ${loket.is_active ? '🔒 Tutup' : '🔓 Buka'}
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        async function toggleLoket(id) {
            await api(`/operator-api/lokets/${id}/toggle`, 'POST');
            loadLokets();
            toast('Status loket diubah');
        }

        function enterLoket(id) {
            selectedLoket = id;
            document.getElementById('loketGrid').style.display = 'none';
            document.getElementById('operatorPanel').classList.add('active');
            document.getElementById('backBtn').classList.add('visible');
            document.getElementById('pageTitle').textContent = 'Operator Panel';
            loadAntrian();
        }

        function showLoketGrid() {
            selectedLoket = null;
            document.getElementById('loketGrid').style.display = 'grid';
            document.getElementById('operatorPanel').classList.remove('active');
            document.getElementById('backBtn').classList.remove('visible');
            document.getElementById('pageTitle').textContent = 'Pilih Loket';
            loadLokets();
        }

        // Load Antrian
        async function loadAntrian() {
            if (!selectedLoket) return;
            const data = await api(`/operator-api/lokets/${selectedLoket}/antrian`);

            queueData.waiting = data.waiting || [];
            queueData.pending = data.pending || [];
            queueData.finished = data.finished || [];

            document.getElementById('countWaiting').textContent = queueData.waiting.length;
            document.getElementById('countPending').textContent = queueData.pending.length;
            document.getElementById('countFinished').textContent = queueData.finished.length;

            // Update current
            if (data.current) {
                setCurrentCall(data.current);
            } else {
                clearCurrentCall();
            }

            renderQueue();
        }

        function setCurrentCall(antrian) {
            const el = document.getElementById('currentCall');
            el.classList.add('has-call');
            document.getElementById('currentNumber').textContent = antrian.nomor_lengkap;
            document.getElementById('currentNumber').classList.remove('empty');
            document.getElementById('currentService').textContent = antrian.layanan?.nama_layanan || '';
            document.getElementById('activeActions').style.display = 'grid';
            document.getElementById('finishBtn').style.display = 'block';
            document.getElementById('btnPanggil').disabled = true;
            document.getElementById('btnPanggil').style.opacity = '0.5';
            renderQueue();
        }

        function clearCurrentCall() {
            const el = document.getElementById('currentCall');
            el.classList.remove('has-call');
            document.getElementById('currentNumber').textContent = '---';
            document.getElementById('currentNumber').classList.add('empty');
            document.getElementById('currentService').textContent = '';
            document.getElementById('activeActions').style.display = 'none';
            document.getElementById('finishBtn').style.display = 'none';
            document.getElementById('btnPanggil').disabled = false;
            document.getElementById('btnPanggil').style.opacity = '1';
            renderQueue();
        }

        // Tabs
        function switchTab(tab) {
            currentTab = tab;
            document.querySelectorAll('.queue-tab').forEach(t => t.classList.remove('active'));
            document.querySelector(`.queue-tab[data-tab="${tab}"]`).classList.add('active');
            renderQueue();
        }

        function renderQueue() {
            const list = document.getElementById('queueList');
            const items = queueData[currentTab] || [];

            if (!items.length) {
                list.innerHTML = `<div class="queue-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg><p>Tidak ada antrian</p></div>`;
                return;
            }

            list.innerHTML = items.map(item => {
                const badgeClass = `q-badge-${item.status}`;
                let actionBtn = '';
                const hasActiveCalling = document.getElementById('btnPanggil').disabled;
                if (currentTab === 'pending' && (item.status === 'skip' || item.status === 'waiting') && !hasActiveCalling) {
                    actionBtn = `<button class="btn btn-primary btn-sm q-action" onclick="panggilNomor(${item.id})">📢 Panggil</button>`;
                }
                return `
                    <div class="queue-item">
                        <div class="left">
                            <span class="q-number">${item.nomor_lengkap}</span>
                            <span class="q-service">${item.layanan?.nama_layanan || ''}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <span class="q-badge ${badgeClass}">${item.status}</span>
                            ${actionBtn}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Actions
        async function panggilBerikutnya() {
            const data = await api(`/operator-api/lokets/${selectedLoket}/panggil`, 'POST');
            if (data.success) {
                toast(`Memanggil: ${data.antrian.nomor_lengkap}`);
            } else {
                toast(data.message || 'Tidak ada antrian menunggu');
            }
            loadAntrian();
        }

        async function panggilUlang() {
            const data = await api(`/operator-api/lokets/${selectedLoket}/panggil-ulang`, 'POST');
            if (data.success) toast(`Panggil ulang: ${data.antrian.nomor_lengkap}`);
            else toast(data.message);
        }

        async function skipAntrian() {
            await api(`/operator-api/lokets/${selectedLoket}/skip`, 'POST');
            toast('Antrian di-skip');
            loadAntrian();
        }

        async function selesai() {
            await api(`/operator-api/lokets/${selectedLoket}/selesai`, 'POST');
            toast('Antrian selesai');
            loadAntrian();
        }

        async function panggilNomor(antrianId) {
            const data = await api(`/operator-api/lokets/${selectedLoket}/panggil-nomor/${antrianId}`, 'POST');
            if (data.success) toast(`Memanggil: ${data.antrian.nomor_lengkap}`);
            loadAntrian();
        }

        // Real-time via Reverb/Echo
        function initEcho() {
            if (window.Echo) {
                window.Echo.channel('antrian')
                    .listen('.antrian.called', (data) => {
                        if (selectedLoket) loadAntrian();
                    })
                    .listen('.antrian.updated', () => {
                        if (selectedLoket) loadAntrian();
                    });
            } else {
                setTimeout(initEcho, 200);
            }
        }
        initEcho();

        // Init
        loadLokets();
    </script>
</body>
</html>
