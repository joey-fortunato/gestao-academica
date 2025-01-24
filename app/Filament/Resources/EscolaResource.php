<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EscolaResource\Pages;
use App\Filament\Resources\EscolaResource\RelationManagers;
use App\Models\Escola;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EscolaResource extends Resource
{
    protected static ?string $model = Escola::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Gestão Acadêmica';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            Forms\Components\TextInput::make('codigo_escola')
            ->unique('escolas', 'codigo_escola', ignoreRecord: true) // Garante que o código seja único
            ->nullable()
            ->label('Código da Escola'),
                Forms\Components\TextInput::make('nome')
                ->required()
                ->maxLength(255)
                ->label('Nome da Escola'),
            Forms\Components\Select::make('tipo')
                ->options([
                    'principal' => 'Principal',
                    'filial' => 'Filial',
                ])
                ->default('filial')
                ->label('Tipo de Escola')
                ->default('filial') // Define o valor padrão como "filial"
                 ->disabled() // Torna o campo inativo (não editável)
                 ->dehydrated() // Garante que o valor seja salvo no banco de dados
                 ->hiddenOn('create') // Oculta o campo na página de criação (opcional)
                 ->hiddenOn('edit'), // Oculta o campo na página de edição (opcional),

            Forms\Components\TextInput::make('director')
                ->nullable()
                ->label('Director'),

            Forms\Components\TextInput::make('subdirector')
                ->nullable()
                ->label('Subdirector'),

            Forms\Components\TextInput::make('endereco')
                ->nullable()
                ->label('Endereço'),

            Forms\Components\TextInput::make('telefone')
                ->tel() // Validação de número de telefone
                ->nullable()
                ->label('Telefone'),

            Forms\Components\Textarea::make('observacoes')
                ->nullable()
                ->label('Observações')
                ->columnSpanFull(), // Ocupa toda a largura do formulário
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                ->searchable()
                ->sortable()
                ->label('Nome da Escola'),

            // Comente ou remova a coluna "tipo"
            // Tables\Columns\TextColumn::make('tipo')
            //     ->searchable()
            //     ->sortable()
            //     ->label('Tipo de Escola'),

            Tables\Columns\TextColumn::make('codigo_escola')
                ->searchable()
                ->sortable()
                ->label('Código da Escola'),

            Tables\Columns\TextColumn::make('director')
                ->searchable()
                ->sortable()
                ->label('Diretor'),

            Tables\Columns\TextColumn::make('subdirector')
                ->searchable()
                ->sortable()
                ->label('Subdiretor'),

                Tables\Columns\TextColumn::make('endereco')
                ->searchable()
                ->sortable()
                ->limit(15)
                ->label('Endereço'),

            Tables\Columns\TextColumn::make('telefone')
                ->searchable()
                ->sortable()
                ->label('Telefone'),
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
            'index' => Pages\ListEscolas::route('/'),
            'create' => Pages\CreateEscola::route('/create'),
            'edit' => Pages\EditEscola::route('/{record}/edit'),
        ];
    }
}
