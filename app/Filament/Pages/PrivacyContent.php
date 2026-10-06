<?php

namespace App\Filament\Pages;

class PrivacyContent extends LegalContent
{
    protected static string $settingKey = 'privacy';

    protected static ?string $navigationLabel = 'Privacy Policy';

    protected static ?string $title = 'Privacy Policy';

    protected static ?int $navigationSort = 6;

    protected static function routeName(): string
    {
        return 'privacy';
    }
}
