<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Matkul;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dosen_id')
                    ->label('Dosen Pengampu')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('matakuliah_id')
                    ->label('Mata Kuliah')
                    ->relationship('matakuliah', 'nama_matkul')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama_kelas')
                    ->label('Nama Ruangan')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dosen.nama')->label('Dosen'),
                Tables\Columns\TextColumn::make('matakuliah.nama_matkul')->label('Mata Kuliah'),
                Tables\Columns\TextColumn::make('nama_kelas')->label('Ruangan'),
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
