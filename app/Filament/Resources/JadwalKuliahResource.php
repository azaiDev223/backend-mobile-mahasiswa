<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalKuliahResource\Pages;
use App\Models\JadwalKuliah;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalKuliahResource extends Resource
{
    protected static ?string $model = JadwalKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Jadwal Kuliah';
    protected static ?string $pluralModelLabel = 'Jadwal Kuliah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'ruangan')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                    ])
                    ->required(),

                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required(),

                Forms\Components\TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required(),

                Forms\Components\TextInput::make('ruangan')
                    ->label('Ruangan')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.ruangan')->label('Kelas'),
                Tables\Columns\TextColumn::make('hari'),
                Tables\Columns\TextColumn::make('jam_mulai')->label('Mulai'),
                Tables\Columns\TextColumn::make('jam_selesai')->label('Selesai'),
                Tables\Columns\TextColumn::make('ruangan'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalKuliahs::route('/'),
            'create' => Pages\CreateJadwalKuliah::route('/create'),
            'edit' => Pages\EditJadwalKuliah::route('/{record}/edit'),
        ];
    }
}
