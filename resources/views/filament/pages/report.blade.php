<x-filament-panels::page>
    <style>
        .report-filter {
            display: flex;
            align-items: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 1.25rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }
        .report-filter label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-filter input[type="date"] {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 0.875rem;
            backdrop-filter: blur(4px);
        }
        .report-filter input[type="date"]:focus {
            outline: none;
            border-color: #fff;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .stat-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f97316, #fb923c); }
        .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #f87171); }
        .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .stat-card.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }
        .stat-card.blue .stat-icon { background: #eff6ff; }
        .stat-card.green .stat-icon { background: #ecfdf5; }
        .stat-card.orange .stat-icon { background: #fff7ed; }
        .stat-card.red .stat-icon { background: #fef2f2; }
        .stat-card.purple .stat-icon { background: #f5f3ff; }
        .stat-card.amber .stat-icon { background: #fffbeb; }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .report-section {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
            margin-bottom: 1.5rem;
        }
        .report-section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .report-section-header .icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .report-section-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
        }
        .report-section-header p {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 0.1rem;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table thead th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            background: #f8fafc;
        }
        .report-table tbody td {
            padding: 0.85rem 1.5rem;
            font-size: 0.875rem;
            color: #475569;
            border-top: 1px solid #f1f5f9;
        }
        .report-table tbody tr:hover {
            background: #f8fafc;
        }
        .report-table .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-blue { background: #eff6ff; color: #2563eb; }
        .badge-purple { background: #f5f3ff; color: #7c3aed; }

        .peak-chart {
            padding: 1.5rem;
        }
        .peak-bar-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .peak-bar-row .hour-label {
            width: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            font-variant-numeric: tabular-nums;
            text-align: right;
        }
        .peak-bar-row .bar-container {
            flex: 1;
            height: 28px;
            background: #f1f5f9;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }
        .peak-bar-row .bar-fill {
            height: 100%;
            border-radius: 6px;
            display: flex;
            align-items: center;
            padding-left: 10px;
            transition: width 0.5s ease;
            min-width: fit-content;
        }
        .peak-bar-row .bar-fill span {
            font-size: 0.7rem;
            font-weight: 700;
            color: #fff;
        }
        .peak-bar-row .bar-fill.normal { background: linear-gradient(90deg, #6366f1, #818cf8); }
        .peak-bar-row .bar-fill.peak { background: linear-gradient(90deg, #ef4444, #f87171); }
        .peak-bar-row .peak-badge {
            font-size: 0.7rem;
            font-weight: 800;
            color: #ef4444;
            width: 50px;
            text-align: center;
        }

        .two-col-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 1024px) {
            .two-col-grid { grid-template-columns: 1fr; }
        }

        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .empty-state .empty-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        /* Dark mode support */
        .dark .stat-card, .dark .report-section { background: #1e293b; border-color: #334155; }
        .dark .stat-card .stat-value { color: #f1f5f9; }
        .dark .stat-card .stat-label { color: #64748b; }
        .dark .report-section-header h3 { color: #f1f5f9; }
        .dark .report-table thead th { background: #0f172a; color: #64748b; }
        .dark .report-table tbody td { color: #cbd5e1; border-color: #334155; }
        .dark .report-table tbody tr:hover { background: #334155; }
        .dark .peak-bar-row .bar-container { background: #334155; }
        .dark .peak-bar-row .hour-label { color: #94a3b8; }
    </style>

    @php $report = $this->getReportData(); @endphp

    {{-- Date Filter --}}
    <div class="report-filter">
        <div>
            <label>📅 Dari Tanggal</label>
            <input type="date" wire:model.live="dateFrom">
        </div>
        <div>
            <label>📅 Sampai Tanggal</label>
            <input type="date" wire:model.live="dateTo">
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-icon">📋</div>
            <div class="stat-value">{{ $report['totalAntrian'] }}</div>
            <div class="stat-label">Total Antrian</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon">🔔</div>
            <div class="stat-value">{{ $report['totalPending'] }}</div>
            <div class="stat-label">Belum Dipanggil</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $report['totalFinished'] }}</div>
            <div class="stat-label">Selesai Dilayani</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon">⏭</div>
            <div class="stat-value">{{ $report['totalSkip'] }}</div>
            <div class="stat-label">Di-skip</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">⏱</div>
            <div class="stat-value">{{ $report['overallAvgWait'] }}</div>
            <div class="stat-label">Rata-rata Tunggu</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon">🏢</div>
            <div class="stat-value">{{ $report['overallAvgService'] }}</div>
            <div class="stat-label">Rata-rata Waktu Layanan</div>
        </div>
    </div>

    {{-- Two Column Tables --}}
    <div class="two-col-grid">
        {{-- Wait Time --}}
        <div class="report-section">
            <div class="report-section-header">
                <div class="icon" style="background: #eff6ff;">⏱</div>
                <div>
                    <h3>Waktu Tunggu per Layanan</h3>
                    <p>Dari ambil antrian sampai dipanggil</p>
                </div>
            </div>
            @if(count($report['avgWaitTime']) > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Rata-rata</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['avgWaitTime'] as $item)
                            <tr>
                                <td style="font-weight: 600; color: #1e293b;">{{ $item['layanan'] }}</td>
                                <td><span class="badge badge-blue">{{ $item['avg_wait'] }}</span></td>
                                <td>{{ $item['total'] }} antrian</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>Belum ada data pada periode ini</p>
                </div>
            @endif
        </div>

        {{-- Service Time --}}
        <div class="report-section">
            <div class="report-section-header">
                <div class="icon" style="background: #f5f3ff;">🏢</div>
                <div>
                    <h3>Waktu Layanan per Loket</h3>
                    <p>Dari dipanggil sampai selesai</p>
                </div>
            </div>
            @if(count($report['avgServiceTime']) > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Loket</th>
                            <th>Rata-rata</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['avgServiceTime'] as $item)
                            <tr>
                                <td style="font-weight: 600; color: #1e293b;">{{ $item['loket'] }}</td>
                                <td><span class="badge badge-purple">{{ $item['avg_service'] }}</span></td>
                                <td>{{ $item['total'] }} antrian</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>Belum ada data pada periode ini</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Peak Hours --}}
    <div class="report-section">
        <div class="report-section-header">
            <div class="icon" style="background: #fef2f2;">🔥</div>
            <div>
                <h3>Jam Sibuk (Peak Hours)</h3>
                <p>Distribusi antrian per jam — merah = jam tersibuk</p>
            </div>
        </div>
        <div class="peak-chart">
            @if(count($report['peakHours']) > 0)
                @php $maxTotal = $report['peakHours']->max('total'); @endphp
                @foreach($report['peakHours'] as $item)
                    @php
                        $pct = $maxTotal > 0 ? ($item['total'] / $maxTotal) * 100 : 0;
                        $isPeak = $item['total'] === $maxTotal;
                    @endphp
                    <div class="peak-bar-row">
                        <div class="hour-label">{{ $item['hour'] }}</div>
                        <div class="bar-container">
                            <div class="bar-fill {{ $isPeak ? 'peak' : 'normal' }}" style="width: {{ max($pct, 5) }}%">
                                <span>{{ $item['total'] }}</span>
                            </div>
                        </div>
                        <div class="peak-badge">{{ $isPeak ? '🔥 PEAK' : '' }}</div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">📊</div>
                    <p>Belum ada data pada periode ini</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
