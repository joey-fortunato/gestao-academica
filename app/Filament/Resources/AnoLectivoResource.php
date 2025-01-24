<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnoLectivoResource\Pages;
use App\Filament\Resources\AnoLectivoResource\RelationManagers;
use App\Models\AnoLectivo;
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

class AnoLectivoResource extends Resource
{
    protected static ?string $model = AnoLectivo::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Ano Lectivo';
    protected static ?string $navigationGroup = 'Gestão Acadêmica';
    protected static ?string $modelLabel = 'Ano Lectivo';
    protected static ?string $pluralModelLabel = 'Anos Lectivos';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('Preencha o formulário abaixo para cadastrar um novo ano lectivo.')
            ->schema([
                Forms\Components\TextInput::make('ano')
                ->label('Ano Lectivo')
                ->required()
                ->placeholder('Ex: 2024/2025')
                ->id('ano-lectivo-input') // ID para o campo
                ->regex('/^\d{4}\/\d{4}$/') // Valida o formato AAAA/AAAA
                ->unique() // Garante que o ano letivo seja único
                ->helperText('Insira o ano lectivo no formato AAAA/AAAA (ex: 2024/2025)')
                ->extraAttributes(['maxlength' => 9]), // Limita o campo a 9 caracteres

            Forms\Components\DatePicker::make('data_inicio')
                ->label('Data de Início')
                ->required(),

            Forms\Components\DatePicker::make('data_fim')
                ->label('Data de Término')
                ->required(),

            Forms\Components\Toggle::make('ativo')
                ->label('Ano Lectivo Ativo?')
                ->default(true),
            ])->columns(3)
            ->afterStateHydrated(function () {
                // Adiciona o script ao formulário
                echo '<script src="/js/ano-lectivo-mask.js"></script>';})
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ano')
                ->label('Ano Letivo')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('data_inicio')
                ->label('Data de Início')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('data_fim')
                ->label('Data de Término')
                ->date()
                ->sortable(),

            Tables\Columns\IconColumn::make('ativo')
                ->label('Ativo?')
                ->boolean(),
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
            'index' => Pages\ListAnoLectivos::route('/'),
            'create' => Pages\CreateAnoLectivo::route('/create'),
            'edit' => Pages\EditAnoLectivo::route('/{record}/edit'),
        ];
    }
}
