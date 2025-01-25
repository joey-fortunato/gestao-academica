<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfessorResource\Pages;
use App\Filament\Resources\ProfessorResource\RelationManagers;
use App\Models\Professor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Professores';
    protected static ?string $navigationGroup = 'Gestão Acadêmica';
    protected static ?string $modelLabel = 'Professor';
    protected static ?string $pluralModelLabel = 'Professores';




    public static function form(Form $form): Form
    {
        $user = Auth::user();
        return $form

        ->schema([
        Section::make('')
        ->description('Preencha o formulário abaixo para cadastrar um professor.')
        ->schema([
            Forms\Components\TextInput::make('nome')
                ->required()
                ->suffixIcon('heroicon-o-clipboard-document-list')
                ->suffixIconColor('primary')
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->suffixIcon('heroicon-o-at-symbol')
                ->suffixIconColor('primary')
                ->maxLength(255),
            Forms\Components\TextInput::make('telefone')
                ->tel()
                ->required()
                ->suffixIcon('heroicon-o-phone')
                        ->suffixIconColor('primary')
                        ->tel()
                        ->numeric() // Aceita apenas números
                        ->minLength(9) // Mínimo de 9 dígitos
                        ->mask('999999999') // Máscara simples
                ->placeholder('Ex: 912345678')
                ->maxLength(9),
                Forms\Components\Select::make('disciplinas')
                ->relationship('disciplinas', 'nome')
                ->required()
                ->preload()
                ->multiple()
                ->label('Disciplinas')
                ->suffixIcon('heroicon-o-academic-cap')
                    ->suffixIconColor('primary'),

                Forms\Components\Hidden::make('escola_id') // Campo oculto para escola_id
                ->default($user->escola_id)
                ->dehydrated(),
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
            Tables\Columns\TextColumn::make('nome')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('telefone')
                ->searchable(),
            Tables\Columns\TextColumn::make('disciplinas.nome') // Exibe as disciplinas associadas
                ->label('Disciplinas')
                ->badge(), // Exibe as disciplinas como badges
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
            'index' => Pages\ListProfessors::route('/'),
            'create' => Pages\CreateProfessor::route('/create'),
            'edit' => Pages\EditProfessor::route('/{record}/edit'),
        ];
    }
}
