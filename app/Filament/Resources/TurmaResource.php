<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TurmaResource\Pages;
use App\Filament\Resources\TurmaResource\RelationManagers;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;


class TurmaResource extends Resource
{
    protected static ?string $model = Turma::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Gestão Acadêmica';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('Preencha o formulário abaixo para cadastrar um novo aluno.')
             ->schema([
                Forms\Components\TextInput::make('nome')
                ->label('Nome da Turma')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('curso_id')
                ->label('Curso')
                ->relationship('curso', 'nome')
                ->required(),

            Forms\Components\Select::make('ano_letivo_id')
                ->label('Ano Letivo')
                ->relationship('anoLectivo', 'ano')
                ->required(),

            Forms\Components\Select::make('turno')
                ->label('Turno')
                ->options([
                    'Manhã' => 'Manhã',
                    'Tarde' => 'Tarde',
                    'Noite' => 'Noite',
                ])
                ->required(),

            Forms\Components\Select::make('sala')
                ->label('Sala')
                ->options(array_combine(
                    range(1, 20),
                    array_map(fn ($i) => "Sala $i", range(1, 20))
                ))
                ->required(),
         ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function ($query) {
            // Filtra os alunos pela escola do usuário logado
            return $query->where('escola_id', auth()->user()->escola_id);
        })
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTurmas::route('/'),
            'create' => Pages\CreateTurma::route('/create'),
            'edit' => Pages\EditTurma::route('/{record}/edit'),
        ];
    }
}
