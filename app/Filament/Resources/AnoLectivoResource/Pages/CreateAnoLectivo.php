<?php

namespace App\Filament\Resources\AnoLectivoResource\Pages;

use App\Filament\Resources\AnoLectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnoLectivo extends CreateRecord
{
    protected static string $resource = AnoLectivoResource::class;

    protected function getFormActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Criar')
                ->submit('Criar'), // Botão "Criar"

            Actions\Action::make('cancel') // Botão "Cancelar"
                ->label('Cancelar')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')), // Redireciona para a listagem
        ];
    }
}
