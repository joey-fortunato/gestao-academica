<?php

namespace App\Filament\Resources\TurmaResource\Pages;

use App\Filament\Resources\TurmaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTurma extends CreateRecord
{
    protected static string $resource = TurmaResource::class;

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
