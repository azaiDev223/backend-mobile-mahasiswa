<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeatureResource\Pages;
use App\Filament\Resources\FeatureResource\RelationManagers;
use App\Models\Feature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Konten Halaman';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- BAGIAN YANG DIPERBARUI ---
                // Menggunakan Select untuk memilih ikon agar lebih user-friendly
                Forms\Components\Select::make('icon')
                    ->label('Ikon Fitur')
                    ->options([
                        // Daftar ikon Font Awesome yang umum digunakan
                        'fas fa-user-graduate' => 'Mahasiswa',
                        'fas fa-chalkboard-teacher' => 'Dosen',
                        'fas fa-book' => 'Mata Kuliah',
                        'fas fa-calendar-alt' => 'Jadwal',
                        'fas fa-file-alt' => 'Laporan',
                        'fas fa-dollar-sign' => 'Keuangan',
                        'fas fa-bullhorn' => 'Pengumuman',
                        'fas fa-comments' => 'Pesan & Saran',
                        'fas fa-cogs' => 'Pengaturan',
                        'fas fa-building-columns' => 'Universitas',
                    ])
                    ->searchable() // Membuat dropdown bisa dicari
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Judul Fitur')
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label('Deskripsi Singkat')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan ikon di tabel
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ikon')
                    ->fontFamily('FontAwesome') // Gunakan font Font Awesome
                    ->size('2xl'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
            'create' => Pages\CreateFeature::route('/create'),
            'edit' => Pages\EditFeature::route('/{record}/edit'),
        ];
    }
}
