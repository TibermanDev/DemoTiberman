<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Halaman pengaturan isi satu halaman situs. Isinya disimpan sebagai satu
 * baris Setting (kolom JSON) dengan kunci static::$settingKey, lalu dibaca
 * view lewat cms('<kunci>.<field>').
 *
 * @property-read Schema $form
 */
abstract class ContentPage extends Page
{
    protected static string $settingKey;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** URL publik halaman ini, untuk tombol "Lihat halaman". */
    abstract protected function publicUrl(): string;

    public function mount(): void
    {
        $this->form->fill(Setting::group(static::$settingKey));
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Simpan')->submit('save')->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat halaman')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url($this->publicUrl(), shouldOpenInNewTab: true),
        ];
    }

    public function save(): void
    {
        // Field yang tidak ada di form (mis. dari versi lama) tetap disimpan.
        Setting::put(static::$settingKey, array_replace(Setting::group(static::$settingKey), $this->form->getState()));

        Notification::make()->title('Tersimpan')->success()->send();
    }
}
