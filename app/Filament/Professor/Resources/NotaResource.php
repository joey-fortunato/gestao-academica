<?php

namespace App\Filament\Professor\Resources;

use App\Filament\Professor\Resources\NotaResource\Pages;
use App\Filament\Professor\Resources\NotaResource\RelationManagers;
use App\Models\Nota;
use App\Models\Aluno;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class NotaResource extends Resource
{
    protected static ?string $model = Nota::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Lançamento de notas')
                ->description('Preencha as informações abaixo para lançar uma nota.')
            ->schema([
                Forms\Components\Select::make('aluno_id')
                ->options(Aluno::orderBy('nome', 'asc')->pluck('nome', 'id'))
                ->label('Aluno')
                ->searchable()
                ->preload()
                ->relationship('aluno', 'nome')
                ->required(),
            Forms\Components\Select::make('turma_id')
                ->label('Turma')
                ->relationship('turma', 'nome')
                ->required(),
            Forms\Components\Select::make('trimestre_id')
                ->label('Trimestre')
                ->relationship('trimestre', 'nome')
                ->required(),
            Forms\Components\TextInput::make('mac')
                ->label('MAC')
                ->numeric()

                ->required(),
            Forms\Components\TextInput::make('npp')
                ->label('NPP')
                ->numeric()
                ->rule('regex:/^(20(\.0{1,2})?|([0-1]?\d)(\.\d{1,2})?)$/')
                ->required(),
            Forms\Components\TextInput::make('npt')
                ->label('NPT')
                ->numeric()
                ->minValue(0) // Nota mínima: 0
                ->maxValue(20) // Nota máxima: 20
                ->required(),
                Forms\Components\TextInput::make('media')
                ->label('Média')
                ->numeric()
                ->reactive()
                ->disabled() // Bloqueia o campo
                ->dehydrated() // Mantém o valor no banco de dados
                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                    // Calcula a média sempre que as notas são alteradas
                    $mac = $get('mac');
                    $npp = $get('npp');
                    $npt = $get('npt');

                    if ($mac !== null && $npp !== null && $npt !== null) {
                        $media = ($mac + $npp + $npt) / 3;
                        $set('media', round($media)); // Arredonda a média
                    }
                }),
            ])->columns(3)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            'index' => Pages\ListNotas::route('/'),
            'create' => Pages\CreateNota::route('/create'),
            'edit' => Pages\EditNota::route('/{record}/edit'),
        ];
    }
}
