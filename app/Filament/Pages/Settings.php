<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Display';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.settings';

    public ?string $youtube_url = '';
    public ?string $running_text = '';

    public function mount(): void
    {
        $this->youtube_url = Setting::get('youtube_url', '');
        $this->running_text = Setting::get('running_text', 'Selamat datang di layanan kami. Silakan ambil nomor antrian.');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('youtube_url')
                    ->label('YouTube URL')
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->helperText('URL video YouTube yang akan ditampilkan di halaman display. Kosongkan jika tidak ingin menampilkan video.')
                    ->url()
                    ->nullable(),
                Textarea::make('running_text')
                    ->label('Running Text')
                    ->placeholder('Teks berjalan di halaman display...')
                    ->helperText('Teks yang akan berjalan di bagian bawah halaman display.')
                    ->rows(3),
            ]);
    }

    public function save(): void
    {
        Setting::set('youtube_url', $this->youtube_url ?? '');
        Setting::set('running_text', $this->running_text ?? '');

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
