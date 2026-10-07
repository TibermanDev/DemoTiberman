<?php

namespace App\Filament\Pages;

class DisclaimerContent extends LegalContent
{
    protected static string $settingKey = 'disclaimer';

    protected static ?string $navigationLabel = 'Disclaimer';

    protected static ?string $title = 'Disclaimer';

    protected static ?int $navigationSort = 6;

    protected static function routeName(): string
    {
        return 'disclaimer';
    }
}
