<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ShortcutLinks extends Widget
{
    protected static string $view = 'filament.widgets.shortcut-links';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -1;
}
