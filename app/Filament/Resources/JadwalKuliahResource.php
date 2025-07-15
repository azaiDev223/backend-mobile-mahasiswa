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
use Illuminate\Database\Eloquent\Builder;

class JadwalKuliahResource extends Resource
{
    protected static ?string $model = JadwalKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $pluralLabel = 'Jadwal Kuliah';
    // protected static ?string $label = 'KHS';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kelas_id')
                    ->label('Kelas')
                    // Parameter kedua ('nama_kelas') sekarang diabaikan karena kita akan membuat label kustom.
                    // Parameter ketiga adalah closure untuk memodifikasi query, kita gunakan untuk eager loading.
                    ->relationship(
                        name: 'kelas',
                        titleAttribute: 'nama_kelas', // Tetap dibutuhkan sebagai fallback/judul
                        modifyQueryUsing: fn(Builder $query) => $query->with(['mataKuliah']) // Eager load relasi mataKuliah
                    )
                    // getOptionLabelFromRecordUsing akan membuat label untuk setiap pilihan
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        // Gabungkan nama kelas, nama mata kuliah, dan semester
                        $namaMatkul = $record->mataKuliah->nama_matkul ?? 'Tanpa Matkul';
                        $semester = $record->mataKuliah->semester ?? '?';
                        return "{$record->nama_kelas} - {$namaMatkul} (Semester {$semester})";
                    })
                    ->searchable()
                    ->preload()
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
                // Forms\Components\Select::make('tahun_akademik')
                //     ->label('Tahun Akademik')
                //     ->options([
                //         '2024/2025 Ganjil' => '2024/2025 Ganjil',
                //         '2024/2025 Genap'  => '2024/2025 Genap',
                //         '2025/2026 Ganjil' => '2025/2026 Ganjil',
                //         '2025/2026 Genap'  => '2025/2026 Genap',
                //         // Tambahkan tahun akademik lainnya di sini
                //     ])
                //     ->required()
                //     ->searchable(), // Agar bisa dicari
                // Forms\Components\TextInput::make('kuota')
                //     ->label('Kuota')
                //     ->numeric()
                //     ->required()
                //     ->minValue(1)
                //     ->maxValue(1000)
                //     ->default(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas'),
                Tables\Columns\TextColumn::make('hari'),
                Tables\Columns\TextColumn::make('jam_mulai')->label('Mulai'),
                Tables\Columns\TextColumn::make('jam_selesai')->label('Selesai'),
                // Tables\Columns\TextColumn::make('tahun_akademik')->label('Tahun Akademik'),
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
