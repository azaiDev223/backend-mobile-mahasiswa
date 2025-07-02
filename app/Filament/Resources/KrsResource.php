<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KrsResource\Pages;
use App\Models\Krs;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class KrsResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $label = 'KRS';
    protected static ?string $pluralLabel = 'KRS Mahasiswa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('id_mahasiswa')
                ->relationship('mahasiswa', 'nama')
                ->required()
                ->label('Mahasiswa'),

            Select::make('semester')
                ->options([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,

                ])
                ->required(),

            Select::make('tahun_akademik')
                ->options([
                    '2023/2024' => '2023/2024',
                    '2024/2025' => '2024/2025',
                ])
                ->required(),

            Select::make('status_krs')
                ->options([
                    'Diajukan' => 'Diajukan',
                    'Disetujui' => 'Disetujui',
                    'Ditolak' => 'Ditolak',
                ])
                ->default('Diajukan')
                ->required(),

            Repeater::make('detail')
                ->relationship() // Krs hasMany KrsDetail
                ->label('Mata Kuliah')
                ->schema([
                    Select::make('jadwal_kuliah_id')
                        ->label('Jadwal Kuliah')
                        ->relationship('jadwalKuliah', 'id')
                        ->searchable()
                        ->required(),
                ])
                ->minItems(1)
                ->createItemButtonLabel('Tambah Mata Kuliah'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('mahasiswa.nama')->label('Mahasiswa')->searchable(),
            TextColumn::make('semester'),
            TextColumn::make('tahun_akademik'),
            TextColumn::make('status_krs')->badge(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKrs::route('/'),
            'create' => Pages\CreateKrs::route('/create'),
            'edit' => Pages\EditKrs::route('/{record}/edit'),
        ];
    }
}
