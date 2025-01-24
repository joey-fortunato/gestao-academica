<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CursoResource\Pages;
use App\Filament\Resources\CursoResource\RelationManagers;
use App\Models\Curso;
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


class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Gestão Acadêmica';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('Preencha o formulário abaixo para cadastrar um novo curso.')
            ->schema([
                Forms\Components\TextInput::make('nome')
                ->label(__('Nome do curso'))
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('descricao')
                ->label(__('Descricao do curso'))
                ->required()
                ->helperText('Máximo de 500 caracteres')
                ->nullable(),
            ])
            ->columns(2)
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('nome')
                    ->label(__('Nome do curso'))
                    ->searchable(),
                    Tables\Columns\TextColumn::make('descricao')
                    ->label(__('Descrição do curso'))
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
            'index' => Pages\ListCursos::route('/'),
            'create' => Pages\CreateCurso::route('/create'),
            'view' => Pages\ViewCurso::route('/{record}'),
            'edit' => Pages\EditCurso::route('/{record}/edit'),
        ];
    }
}
