<?php

namespace App\Filament\Resources\TranskripNilaiResource\Pages;

use App\Filament\Resources\TranskripNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranskripNilai extends EditRecord
{
    protected static string $resource = TranskripNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
