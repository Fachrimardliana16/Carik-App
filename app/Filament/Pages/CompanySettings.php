<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use App\Models\CompanySetting;

class CompanySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Perusahaan';
    protected static ?string $title = 'Pengaturan Perusahaan';
    protected static ?int $navigationSort = 99;
    protected static ?string $navigationGroup = 'Pengaturan';

    protected static string $view = 'filament.pages.company-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = CompanySetting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->tabs([
                    Tabs\Tab::make('Umum')
                        ->schema([
                            Section::make('Informasi Perusahaan')
                                ->schema([
                                    TextInput::make('company_name')
                                        ->label('Nama Instansi / Perusahaan')
                                        ->required()
                                        ->maxLength(255),
                                ]),
                        ]),
                    Tabs\Tab::make('Kontak')
                        ->schema([
                            Section::make('Kontak Instansi')
                                ->description('Informasi ini akan digunakan pada header surat dan kop resmi')
                                ->schema([
                                    Textarea::make('company_address')
                                        ->label('Alamat')
                                        ->rows(3),
                                    TextInput::make('company_phone')
                                        ->label('Telepon')
                                        ->tel(),
                                    TextInput::make('company_email')
                                        ->label('Email')
                                        ->email(),
                                ]),
                        ]),
                    Tabs\Tab::make('Branding')
                        ->schema([
                            Section::make('Logo & Warna')
                                ->description('Logo akan ditampilkan di sidebar, login, dan kop surat. QR Code akan menggunakan brand color.')
                                ->schema([
                                    FileUpload::make('logo_light')
                                        ->label('Logo (Light Mode)')
                                        ->image()
                                        ->directory('branding')
                                        ->imageEditor(),
                                    FileUpload::make('logo_dark')
                                        ->label('Logo (Dark Mode)')
                                        ->image()
                                        ->directory('branding')
                                        ->imageEditor(),
                                    FileUpload::make('favicon')
                                        ->label('Favicon')
                                        ->image()
                                        ->directory('branding')
                                        ->imageEditor(),
                                    ColorPicker::make('primary_color')
                                        ->label('Warna Utama (Primary Color)')
                                        ->required(),
                                ]),
                        ]),
                ]),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            CompanySetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear settings cache
        \App\Services\SettingsService::clearCache();

        Notification::make()
            ->title('Berhasil disimpan!')
            ->success()
            ->send();
    }
}
