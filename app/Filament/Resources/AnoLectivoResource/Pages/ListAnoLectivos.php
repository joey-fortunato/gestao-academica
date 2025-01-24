<?php

namespace App\Filament\Resources\AnoLectivoResource\Pages;

use App\Filament\Resources\AnoLectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnoLectivos extends ListRecords
{
    protected static string $resource = AnoLectivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
