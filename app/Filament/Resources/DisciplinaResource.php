<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisciplinaResource\Pages;
use App\Filament\Resources\DisciplinaResource\RelationManagers;
use App\Models\Disciplina;
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


class DisciplinaResource extends Resource
{
    protected static ?string $model = Disciplina::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Gestão Acadêmica';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                ->description('Preencha o formulário abaixo para cadastrar uma nova disciplina.')
                ->schema([
                    Forms\Components\TextInput::make('nome')
                    ->label(__('Nome da disciplina.'))
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('cursos')
                ->relationship('cursos', 'nome')
                ->required()
                ->label('Curso'),
                Forms\Components\Select::make('classe')
                    ->label('Classe')
                    ->options([
                        '10ª Classe' => '10ª Classe',
                        '11ª Classe' => '11ª Classe',
                        '12ª Classe' => '12ª Classe',
                        '13ª Classe' => '13ª Classe',
                    ])
                    ->required(),
                    Forms\Components\TextInput::make('descricao')
                    ->label(__('Descrição da disciplina')),
                ])
                ->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                ->label(__('Nome da disciplina'))
                ->searchable(),

                Tables\Columns\TextColumn::make('cursos.nome')
                ->label(__('Curso'))
                ->searchable(),

                Tables\Columns\TextColumn::make('classe')
                ->label(__('Classe'))
                ->searchable(),

                Tables\Columns\TextColumn::make('descricao')
                ->label(__('Descrição da disciplina'))
                ->searchable()
                ->limit(20) // Limita a exibição a 50 caracteres na tabela
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
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
            'index' => Pages\ListDisciplinas::route('/'),
            'create' => Pages\CreateDisciplina::route('/create'),
            'view' => Pages\ViewDisciplina::route('/{record}'),
            'edit' => Pages\EditDisciplina::route('/{record}/edit'),
        ];
    }
}
