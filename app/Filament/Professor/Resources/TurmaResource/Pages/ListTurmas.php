<?php

namespace App\Filament\Professor\Resources\TurmaResource\Pages;

use App\Filament\Professor\Resources\TurmaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTurmas extends ListRecords
{
    protected static string $resource = TurmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
