<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BimbinganResource\Pages;
use App\Models\Bimbingan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BimbinganResource extends Resource
{
    protected static ?string $model = Bimbingan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Bimbingan';
    protected static ?string $modelLabel = 'Bimbingan';
    protected static ?string $pluralModelLabel = 'Bimbingan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mahasiswa_id')
                    ->label('Mahasiswa')
                    ->relationship('mahasiswa', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('dosen_id')
                    ->label('Dosen')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\DateTimePicker::make('tanggal_bimbingan')
                    ->label('Tanggal Bimbingan')
                    ->required(),

                Forms\Components\TextInput::make('topik')
                    ->label('Topik')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3),

                Forms\Components\Select::make('status')
                    ->options([
                        'Terjadwal' => 'Terjadwal',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ])
                    ->required()
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_bimbingan')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('topik')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->topik),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'Terjadwal',
                        'success' => 'Selesai',
                        'danger' => 'Dibatalkan',
                    ]),
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
            'index' => Pages\ListBimbingans::route('/'),
            'create' => Pages\CreateBimbingan::route('/create'),
            'edit' => Pages\EditBimbingan::route('/{record}/edit'),
        ];
    }
}
