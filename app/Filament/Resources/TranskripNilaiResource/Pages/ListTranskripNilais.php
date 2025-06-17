<?php

namespace App\Filament\Resources\TranskripNilaiResource\Pages;

use App\Filament\Resources\TranskripNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranskripNilais extends ListRecords
{
    protected static string $resource = TranskripNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
