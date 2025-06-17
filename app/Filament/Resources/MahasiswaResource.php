<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Filament\Resources\MahasiswaResource\RelationManagers;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('nim')
                ->label('NIM')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('nama')
                ->label('Nama')
                ->required(),

            Forms\Components\Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->hiddenOn('edit'), // agar tidak muncul saat edit

            Forms\Components\TextInput::make('no_hp')
                ->label('No HP')
                ->required(),

            Forms\Components\TextInput::make('alamat')
                ->label('Alamat')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->required(),

            Forms\Components\TextInput::make('angkatan')
                ->label('Angkatan')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('program_studi_id')
                ->label('Program Studi')
                ->relationship('programStudi', 'nama_prodi')
                ->required(),

            Forms\Components\Select::make('dosen_id')
                ->label('Dosen Pembimbing')
                ->relationship('dosen', 'nama')
                ->searchable()
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('nim')->label('NIM')->searchable(),
            Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
            Tables\Columns\TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
            Tables\Columns\TextColumn::make('email')->label('Email'),
            Tables\Columns\TextColumn::make('no_hp')->label('No HP'),
            Tables\Columns\TextColumn::make('angkatan')->label('Angkatan'),
            Tables\Columns\TextColumn::make('programStudi.nama_prodi')->label('Program Studi'),
            Tables\Columns\TextColumn::make('dosen.nama')->label('Dosen Pembimbing')->sortable(),
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
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
