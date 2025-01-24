<?php

namespace App\Filament\Resources\AlunoResource\Pages;

use App\Filament\Resources\AlunoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAluno extends CreateRecord
{
    protected static string $resource = AlunoResource::class;

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
