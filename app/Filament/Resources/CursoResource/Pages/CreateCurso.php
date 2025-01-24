<?php

namespace App\Filament\Resources\CursoResource\Pages;

use App\Filament\Resources\CursoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCurso extends CreateRecord
{
    protected static string $resource = CursoResource::class;
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
