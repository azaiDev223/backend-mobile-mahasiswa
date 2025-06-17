<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramStudiResource\Pages;
use App\Filament\Resources\ProgramStudiResource\RelationManagers;
use App\Models\ProgramStudi;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramStudiResource extends Resource
{
    protected static ?string $model = ProgramStudi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('kode_prodi')
                    ->required()
                    ->label('kode_prodi')
                    ->maxLength(50),
                TextInput::make('nama_prodi')
                    ->required()
                    ->label('nama_prodi')
                    ->maxLength(50),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('kode_prodi')
                    ->label('Kode Prodi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_prodi')
                    ->label('Nama Prodi')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProgramStudis::route('/'),
            'create' => Pages\CreateProgramStudi::route('/create'),
            'edit' => Pages\EditProgramStudi::route('/{record}/edit'),
        ];
    }
}
