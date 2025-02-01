<?php

namespace App\Filament\Professor\Resources;

use App\Filament\Professor\Resources\TurmaResource\Pages;
use App\Filament\Professor\Resources\TurmaResource\RelationManagers;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TurmaResource extends Resource
{
    protected static ?string $model = Turma::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome da Turma')
                    ->required(),
                Forms\Components\Select::make('curso_id')
                    ->label('Curso')
                    ->relationship('curso', 'nome')
                    ->required(),
                Forms\Components\Select::make('professor_id')
                    ->label('Professor')
                    ->relationship('professor', 'nome')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anoLectivo.ano')
                    ->label('Ano Lectivo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome da Turma')
                    ->searchable(),
                Tables\Columns\TextColumn::make('abreviacao')
                    ->label('Abreviação')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classe')
                    ->label('Classe'),
                Tables\Columns\TextColumn::make('curso.nome')
                    ->label('Curso')
                    ->sortable(),
                Tables\Columns\TextColumn::make('turno')
                    ->label('Turno'),
                Tables\Columns\TextColumn::make('sala')
                    ->label('Sala'),
                Tables\Columns\TextColumn::make('directorTurma.nome')
                    ->label('Director de Turma'),
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
