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
use Illuminate\Support\Facades\Auth;


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
            ->description('Preencha o formulário abaixo para cadastrar um novo ano lectivo.')
        ->schema([
              // Ano Letivo (Select)
              Forms\Components\Select::make('ano_lectivo_id')
              ->label('Ano Lectivo')
              ->relationship('anoLectivo', 'ano') // Relacionamento com a tabela ano_letivos
              ->required()
              ->suffixIcon('heroicon-o-calendar-date-range')
              ->suffixIconColor('primary')
              ->preload(),

          // Nome da Turma (Calculado automaticamente)
          Forms\Components\TextInput::make('nome')
              ->label('Nome da Turma')
              ->required()
              ->maxLength(255)
              ->disabled() // Desabilita a edição manual
              ->dehydrated()
              ->suffixIcon('heroicon-o-clipboard-document-list')
              ->suffixIconColor('primary')
              ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                $abreviacao = $get('abreviacao');
                $classe = $get('classe');
                $curso = \App\Models\Curso::find($get('curso_id'))?->nome;

                if ($abreviacao && $classe && $curso) {
                    $nomeTurma = "$abreviacao $classe - $curso";
                    $set('nome', $nomeTurma);
                }
            })
            ->reactive(),

          // Abreviação (Input)
          Forms\Components\TextInput::make('abreviacao')
          ->label('Abreviação')
          ->required()
          ->maxLength(10)
          ->suffixIcon('heroicon-o-clipboard-document-list')
          ->suffixIconColor('primary')
          ->reactive() // Torna o campo reativo
          ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
              $abreviacao = $get('abreviacao');
              $classe = $get('classe');
              $curso = \App\Models\Curso::find($get('curso_id'))?->nome;

              if ($abreviacao && $classe && $curso) {
                  $nomeTurma = "$abreviacao $classe - $curso";
                  $set('nome', $nomeTurma);
              }
          }),

          Forms\Components\Select::make('classe')
    ->label('Classe')
    ->options([
        '10ª' => '10ª Classe',
        '11ª' => '11ª Classe',
        '12ª' => '12ª Classe',
        '13ª' => '13ª Classe',
    ])
    ->required()
    ->reactive()
    ->suffixIcon('heroicon-o-clipboard-document-list')
    ->suffixIconColor('primary')
     // Torna o campo reativo
    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
        $abreviacao = $get('abreviacao');
        $classe = $get('classe');
        $curso = \App\Models\Curso::find($get('curso_id'))?->nome;

        if ($abreviacao && $classe && $curso) {
            $nomeTurma = "$abreviacao $classe - $curso";
            $set('nome', $nomeTurma);
        }
    }),

Forms\Components\Select::make('curso_id')
    ->label('Curso')
    ->relationship('curso', 'nome')
    ->required()
    ->preload()
    ->suffixIcon('heroicon-o-academic-cap')
    ->suffixIconColor('primary')
    ->reactive() // Torna o campo reativo
    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
        $abreviacao = $get('abreviacao');
        $classe = $get('classe');
        $curso = \App\Models\Curso::find($get('curso_id'))?->nome;

        if ($abreviacao && $classe && $curso) {
            $nomeTurma = "$abreviacao $classe - $curso";
            $set('nome', $nomeTurma);
        }
    }),

          // Turno (Select)
          Forms\Components\Select::make('turno')
              ->label('Turno')
              ->suffixIcon('heroicon-o-sun')
              ->suffixIconColor('primary')
              ->options([
                  'Manhã' => 'Manhã',
                  'Tarde' => 'Tarde',
                  'Noite' => 'Noite',
              ])
              ->required(),

          // Sala (Select)
          Forms\Components\Select::make('sala')
              ->label('Sala')
              ->options(collect(range(1, 20))->mapWithKeys(fn ($sala) => [$sala => "Sala $sala"]))
              ->suffixIcon('heroicon-o-clipboard-document-list')
              ->suffixIconColor('primary')
              ->required(),

          // Director de Turma (Select)
          Forms\Components\Select::make('director_turma_id')
              ->label('Director de Turma')
              ->relationship('directorTurma', 'nome') // Relacionamento com a tabela professores
              ->required()
              ->suffixIcon('heroicon-o-clipboard-document-list')
              ->suffixIconColor('primary')
              ->preload(),

          // Escola ID (Hidden)
          Forms\Components\Hidden::make('escola_id')
              ->default(auth()->user()->escola_id), // Preenche automaticamente com o ID da escola do usuário logado
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
