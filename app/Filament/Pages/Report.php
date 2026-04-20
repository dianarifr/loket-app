<?php

namespace App\Filament\Pages;

use App\Models\Antrian;
use App\Models\Layanan;
use App\Models\Loket;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Report extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan Antrian';
    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.report';

    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = today()->format('Y-m-d');
        $this->dateTo = today()->format('Y-m-d');
    }

    public function getReportData(): array
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to = Carbon::parse($this->dateTo)->endOfDay();

        // Average Wait Time (created_at → called_at) per layanan
        $avgWaitTime = Antrian::select(
                'layanan_id',
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, called_at)) as avg_wait_seconds'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('called_at')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('layanan_id')
            ->with('layanan')
            ->get()
            ->map(function ($item) {
                return [
                    'layanan' => $item->layanan->nama_layanan ?? '-',
                    'avg_wait' => $this->formatDuration($item->avg_wait_seconds),
                    'avg_wait_seconds' => round($item->avg_wait_seconds),
                    'total' => $item->total,
                ];
            });

        // Average Service Time (called_at → finished_at) per loket
        $avgServiceTime = Antrian::select(
                'loket_id',
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, called_at, finished_at)) as avg_service_seconds'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('called_at')
            ->whereNotNull('finished_at')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('loket_id')
            ->with('loket')
            ->get()
            ->map(function ($item) {
                return [
                    'loket' => $item->loket->nama_loket ?? '-',
                    'avg_service' => $this->formatDuration($item->avg_service_seconds),
                    'avg_service_seconds' => round($item->avg_service_seconds),
                    'total' => $item->total,
                ];
            });

        // Peak Hours
        $peakHours = Antrian::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => str_pad($item->hour, 2, '0', STR_PAD_LEFT) . ':00',
                    'total' => $item->total,
                ];
            });

        // Summary totals
        $totalAntrian = Antrian::whereBetween('created_at', [$from, $to])->count();
        $totalPending = Antrian::whereBetween('created_at', [$from, $to])->where('status', 'waiting')->count();
        $totalFinished = Antrian::whereBetween('created_at', [$from, $to])->where('status', 'finished')->count();
        $totalSkip = Antrian::whereBetween('created_at', [$from, $to])->where('status', 'skip')->count();

        $overallAvgWait = Antrian::whereNotNull('called_at')
            ->whereBetween('created_at', [$from, $to])
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, called_at)'));

        $overallAvgService = Antrian::whereNotNull('called_at')
            ->whereNotNull('finished_at')
            ->whereBetween('created_at', [$from, $to])
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, called_at, finished_at)'));

        return [
            'avgWaitTime' => $avgWaitTime,
            'avgServiceTime' => $avgServiceTime,
            'peakHours' => $peakHours,
            'totalAntrian' => $totalAntrian,
            'totalPending' => $totalPending,
            'totalFinished' => $totalFinished,
            'totalSkip' => $totalSkip,
            'overallAvgWait' => $this->formatDuration($overallAvgWait),
            'overallAvgService' => $this->formatDuration($overallAvgService),
        ];
    }

    private function formatDuration(?float $seconds): string
    {
        if (!$seconds || $seconds <= 0) return '-';

        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;

        if ($minutes >= 60) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return "{$hours}j {$minutes}m";
        }

        return "{$minutes}m {$secs}d";
    }
}
