<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatriculaResource\Pages;
use App\Filament\Resources\MatriculaResource\RelationManagers;
use App\Models\Matricula;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class MatriculaResource extends Resource
{
    protected static ?string $model = Matricula::class;


    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Gestão Acadêmica';


    public static function form(Form $form): Form
    {
        $user = Auth::user();

        return $form
            ->schema([
                Section::make('')
                ->description('Preencha o formulário abaixo para matricular um aluno')
                ->schema([
                    Forms\Components\Select::make('aluno_id')
                        ->label('Nome do Aluno')
                        ->searchable()
                        ->relationship('aluno', 'nome')
                        ->preload()
                        ->suffixIcon('heroicon-o-user')
                        ->suffixIconColor('primary'),
                    Forms\Components\Select::make('turma_id')
                        ->label('Turma')
                        ->searchable()
                        ->relationship('turma', 'nome')
                        ->preload()
                        ->suffixIcon('heroicon-o-user-group')
                        ->suffixIconColor('primary'),
                    Forms\Components\Select::make('curso_id')
                        ->label('Curso')
                        ->searchable()
                        ->relationship('curso', 'nome')
                        ->preload()
                        ->suffixIcon('heroicon-o-book-open')
                        ->suffixIconColor('primary'),
                    Forms\Components\Select::make('turno')
                        ->label('Turno')
                        ->suffixIcon('heroicon-o-sun')
                        ->suffixIconColor('primary')
                        ->options([
                          'Manhã' => 'Manhã',
                          'Tarde' => 'Tarde',
                          'Noite' => 'Noite',
                        ]),
                    Forms\Components\Select::make('sala')
                        ->label('Sala')
                        ->options(collect(range(1, 20))->mapWithKeys(fn ($sala) => [$sala => "Sala $sala"]))
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary'),
                    Forms\Components\DatePicker::make('data_matricula')
                        ->label('Data da Matrícula')
                        ->default(now()) // Define o valor padrão como a data atual
                        ->required()
                        ->disabled()
                        ->dehydrated(),
                    Forms\Components\Hidden::make('escola_id') // Campo oculto para escola_id
                        ->default($user->escola_id)
                        ->dehydrated(),
                ])->columns(3)
            ]);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Depuração: Exibe os dados do formulário antes de criar a matrícula
        dd($data);

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                 // Coluna: Nome do Aluno
            Tables\Columns\TextColumn::make('aluno.nome')
            ->label('Aluno')
            ->searchable()
            ->sortable(),

        // Coluna: Nome da Turma
        Tables\Columns\TextColumn::make('turma.nome')
            ->label('Turma')
            ->searchable()
            ->sortable(),

        // Coluna: Nome do Curso
        Tables\Columns\TextColumn::make('curso.nome')
            ->label('Curso')
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('turno')
            ->label('Turno')
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('sala')
            ->label('Sala')
            ->searchable()
            ->sortable(),

        // Coluna: Data da Matrícula
        Tables\Columns\TextColumn::make('data_matricula')
            ->label('Data da Matrícula')
            ->date() // Formata a data como "Y-m-d"
            ->sortable(),


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
            'index' => Pages\ListMatriculas::route('/'),
            'create' => Pages\CreateMatricula::route('/create'),
            'edit' => Pages\EditMatricula::route('/{record}/edit'),
        ];
    }
}
