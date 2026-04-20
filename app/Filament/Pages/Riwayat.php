<?php

namespace App\Filament\Pages;

use App\Models\Antrian;
use App\Models\Loket;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class Riwayat extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Panggilan';
    protected static ?string $title = 'Riwayat Panggilan';
    protected static ?int $navigationSort = 40;

    protected static string $view = 'filament.pages.riwayat';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Antrian::query()->with(['loket', 'layanan'])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('loket.nama_loket')
                    ->label('Loket')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('layanan.nama_layanan')
                    ->label('Pelayanan')
                    ->searchable(),
                TextColumn::make('nomor_lengkap')
                    ->label('Nomor Antrian')
                    ->weight('bold')
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('called_at')
                    ->label('Mulai')
                    ->dateTime('H:i:s')
                    ->placeholder('-'),
                TextColumn::make('finished_at')
                    ->label('Selesai')
                    ->dateTime('H:i:s')
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'finished' => 'success',
                        'calling' => 'info',
                        'skip' => 'danger',
                        'waiting' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'finished' => 'Selesai',
                        'calling' => 'Calling',
                        'skip' => 'Skip',
                        'waiting' => 'Menunggu',
                        default => $state,
                    }),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')->default(today()),
                        DatePicker::make('sampai')->default(today()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari'], fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['sampai'], fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari'])->format('d/m/Y');
                        if ($data['sampai'] ?? null) $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai'])->format('d/m/Y');
                        return $indicators;
                    }),
                SelectFilter::make('loket_id')
                    ->label('Loket')
                    ->options(Loket::pluck('nama_loket', 'id'))
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'waiting' => 'Menunggu',
                        'calling' => 'Calling',
                        'finished' => 'Selesai',
                        'skip' => 'Skip',
                    ]),
            ])
            ->paginated([10, 20, 50, 100]);
    }
}
