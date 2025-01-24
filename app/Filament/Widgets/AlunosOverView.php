<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;
use App\Models\Aluno;
use App\Models\Curso;

class AlunosOverView extends BaseWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make('Alunos', Aluno::query()->count())
            ->description('Activos')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('Cursos Ministrados', Curso::query()->count())
            ->description('')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('info'),

        ];
    }
}
