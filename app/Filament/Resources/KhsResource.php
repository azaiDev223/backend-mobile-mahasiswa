<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KhsResource\Pages;
use App\Models\Khs;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\MataKuliah;
use Filament\Forms\Components\{Select, Repeater, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Table;

class KhsResource extends Resource
{
    protected static ?string $model = Khs::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('mahasiswa_id')
                ->relationship('mahasiswa', 'nama')
                ->searchable()
                ->required(),

            TextInput::make('semester')
                ->numeric()
                ->required(),

            TextInput::make('tahun_akademik')
                ->required(),

            Repeater::make('details')
                ->relationship()
                ->label('Daftar Nilai Mata Kuliah')
                ->schema([
                    Select::make('mata_kuliah_id')
                        ->label('Mata Kuliah')
                        ->options(function ($get) {
                            $mahasiswaId = $get('mahasiswa_id');
                            $semester = $get('semester');
                            $tahun = $get('tahun_akademik');

                            if (!$mahasiswaId || !$semester || !$tahun) return [];

                            $krs = Krs::where('id_mahasiswa', $mahasiswaId)
                                ->where('semester', $semester)
                                ->where('tahun_akademik', $tahun)
                                ->first();

                            if (!$krs) return [];

                            return KrsDetail::where('krs_id', $krs->id)
                                ->with('jadwalKuliah.kelas.mataKuliah')
                                ->get()
                                ->pluck('jadwalKuliah.kelas.mataKuliah')
                                ->unique('id')
                                ->pluck('nama_matkul', 'id');
                        })
                        ->required()
                        ->searchable(),

                    TextInput::make('nilai')
                        ->numeric()
                        ->required(),

                    Select::make('grade')
                        ->options([
                            'A' => 'A',
                            'B' => 'B',
                            'C' => 'C',
                            'D' => 'D',
                            'E' => 'E',
                            'F' => 'F',
                        ])
                        ->required(),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('mahasiswa.nama')->label('Mahasiswa'),
            TextColumn::make('semester'),
            TextColumn::make('tahun_akademik'),
            TextColumn::make('ips')->label('IPS'),
            TextColumn::make('ipk')->label('IPK'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKhs::route('/'),
            'create' => Pages\CreateKhs::route('/create'),
            'edit' => Pages\EditKhs::route('/{record}/edit'),
        ];
    }
}
