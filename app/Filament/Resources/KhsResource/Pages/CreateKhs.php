<?php

namespace App\Filament\Resources\KhsResource\Pages;

use App\Filament\Resources\KhsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\MataKuliah;

class CreateKhs extends CreateRecord
{
    protected static string $resource = KhsResource::class;


protected function afterCreate(): void
{
    $this->record->refresh();
    $this->hitungIndeksPrestasi();
}

protected function afterSave(): void
{
    $this->record->refresh();
    $this->hitungIndeksPrestasi();
}

protected function hitungIndeksPrestasi(): void
{
    $details = $this->record->details()->with('mataKuliah')->get();
    $totalBobot = 0;
    $totalSks = 0;

    foreach ($details as $detail) {
        $nilai = match ($detail->grade) {
            'A' => 4, 'B' => 3, 'C' => 2, 'D' => 1,
            default => 0,
        };
        $sks = $detail->mataKuliah->sks;
        $totalSks += $sks;
        $totalBobot += $nilai * $sks;
    }

    $ips = $totalSks > 0 ? $totalBobot / $totalSks : 0;
    $ip = $totalBobot;

    // IPK
    $all = \App\Models\Khs::where('mahasiswa_id', $this->record->mahasiswa_id)->with('details.mataKuliah')->get();
    $totalSksAll = 0;
    $totalBobotAll = 0;
    foreach ($all as $khs) {
        foreach ($khs->details as $d) {
            $nilai = match ($d->grade) {
                'A' => 4, 'B' => 3, 'C' => 2, 'D' => 1,
                default => 0,
            };
            $sks = $d->mataKuliah->sks;
            $totalSksAll += $sks;
            $totalBobotAll += $nilai * $sks;
        }
    }

    $ipk = $totalSksAll > 0 ? $totalBobotAll / $totalSksAll : 0;

    $this->record->update([
        'ip' => $ip,
        'ips' => $ips,
        'ipk' => $ipk,
    ]);
}

}

