<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KrsDetailResource\Pages;
use App\Filament\Resources\KrsDetailResource\RelationManagers;
use App\Models\Krs;
use App\Models\KrsDetail;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KrsDetailResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('id_mahasiswa')
                ->relationship('mahasiswa', 'nama')
                ->required(),

            Select::make('status_krs')
                ->options([
                    'Diajukan' => 'Diajukan',
                    'Disetujui' => 'Disetujui',
                    'Ditolak' => 'Ditolak',
                ])
                ->default('Diajukan'),

            Repeater::make('details')
                ->relationship()
                ->schema([
                    Select::make('jadwal_kuliah_id')
                        ->label('Jadwal Kuliah')
                        ->relationship('jadwalKuliah', 'id')
                        ->required(),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.nama'),
                TextColumn::make('semester'),
                TextColumn::make('tahun_akademik'),
                TextColumn::make('status_krs'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKrsDetails::route('/'),
            'create' => Pages\CreateKrsDetail::route('/create'),
            'edit' => Pages\EditKrsDetail::route('/{record}/edit'),
        ];
    }
}
