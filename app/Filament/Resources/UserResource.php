<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\PasswordInput;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('roles')
                ->label('Papéis')
                ->relationship('roles', 'name')
                ->multiple() // Permite selecionar vários papéis
                ->preload(), // Carrega os papéis dinamicamente
            TextInput::make('password')
                ->label('Senha')
                ->password()
                ->revealable()
                ->required()
                ->minLength(8)
                ->dehydrated(fn ($state) => filled($state)) // Mantém o valor apenas se preenchido
                ->dehydrateStateUsing(fn ($state) => Hash::make($state)), // Criptografa a senha
            Forms\Components\Hidden::make('escola_id') // Campo oculto para escola_id
                ->default($user->escola_id)
                ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nome')
                ->searchable(),

            Tables\Columns\TextColumn::make('email')
                ->label('E-mail')
                ->searchable(),

            Tables\Columns\TextColumn::make('roles.name')
                ->label('Papel(éis)')
                ->badge(), // Exibe os papéis como badges

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
