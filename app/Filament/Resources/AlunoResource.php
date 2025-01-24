<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlunoResource\Pages;
use App\Filament\Resources\AlunoResource\RelationManagers;
use App\Models\Aluno;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class AlunoResource extends Resource
{
    protected static ?string $model = Aluno::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Gestão Acadêmica';

    public static function form(Form $form): Form
    {
        $user = Auth::user();

        $provincias = [
            'Bengo',
            'Benguela',
            'Bié',
            'Cabinda',
            'Cuando Cubango',
            'Cuanza Norte',
            'Cuanza Sul',
            'Cunene',
            'Huambo',
            'Huíla',
            'Luanda',
            'Lunda Norte',
            'Lunda Sul',
            'Malanje',
            'Moxico',
            'Namibe',
            'Uíge',
            'Zaire',
        ];

            return $form
                ->schema([
            // Seção 1: Informações Pessoais
            Section::make('Informações Pessoais')
                ->description('Preencha as informações básicas do aluno.')
                ->schema([
                    Forms\Components\TextInput::make('numero_processo')
                        ->label('Nº do Processo')
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome Completo')
                        ->required()
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary')
                        ->maxLength(255),
                    Forms\Components\Hidden::make('escola_id') // Campo oculto para escola_id
                        ->default($user->escola_id)
                        ->dehydrated(),
                    Forms\Components\Select::make('sexo')
                        ->label('Sexo')
                        ->options([
                            'M' => 'Masculino',
                            'F' => 'Feminino',
                        ])
                        ->suffixIcon('heroicon-o-user')
                        ->suffixIconColor('primary')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->suffixIcon('heroicon-o-envelope-open')
                        ->suffixIconColor('primary')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('telefone')
                        ->required()
                        ->suffixIcon('heroicon-o-phone')
                        ->suffixIconColor('primary')
                        ->tel()
                        ->numeric() // Aceita apenas números
                        ->minLength(9) // Mínimo de 9 dígitos
                        ->mask('999 999 999') // Máscara simples
                ->placeholder('Ex: 912 345 678'),
                ])
                ->columns(3), // Divide os campos em 3 colunas

            // Seção 2: Naturalidade e Localização
            Section::make('Naturalidade e Localização')
            ->description('Preencha as informações de naturalidade.')
                ->schema([
                    Forms\Components\TextInput::make('naturalidade')
                    ->required()
                        ->label('Naturalidade')
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary'),
                    Select::make('provincia')
                        ->label('Província')
                        ->required()
                        ->options(array_combine($provincias, $provincias))
                        ->searchable()
                        ->suffixIcon('heroicon-o-home')
                        ->suffixIconColor('primary'),
                    Select::make('municipio')
                    ->required()
                        ->label('Município')
                        ->suffixIcon('heroicon-o-home')
                        ->suffixIconColor('primary')
                        ->options(function (callable $get) {
                            $provincia = $get('provincia');
                            return $provincia ? self::getMunicipios($provincia) : [];
                        })
                        ->searchable()
                        ->reactive(),
                        Forms\Components\TextInput::make('residencia')
                        ->label('Residência')
                        ->required()
                        ->suffixIcon('heroicon-o-home')
                        ->suffixIconColor('primary')
                        ->maxLength(255),
                ])
                ->columns(3),

            // Seção 3: Documentação
            Section::make('Documentação')
            ->description('Preencha com as informações que constam no documento de identificação.')
                ->schema([
                    Forms\Components\TextInput::make('bi')
                    ->label('Nº B.I ou C.N')
                    ->required()
                    ->maxLength(255)
                    ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary'),
                    Forms\Components\DatePicker::make('data_nascimento')
                        ->label('Data de Nascimento')
                        ->required(),
                    DatePicker::make('data_emissao')
                    ->required()
                        ->label('Data de Emissão'),
                    DatePicker::make('data_expiracao')
                    ->required()
                        ->label('Data de Expiração'),
                    Select::make('estado_civil')
                    ->required()
                        ->label('Estado Civil')
                        ->options([
                            'Solteiro' => 'Solteiro',
                            'Casado' => 'Casado',
                            'Divorciado' => 'Divorciado',
                            'Viuvo' => 'Viúvo',
                        ])
                        ->suffixIcon('heroicon-o-heart')
                        ->suffixIconColor('primary'),
                ])
                ->columns(3),

            // Seção 4: Informações dos Pais/Encarregados
            Section::make('Informações dos Pais/Encarregados')
            ->description('Preencha as informações sobre os pais e encarregado de educação.')
                ->schema([
                    Forms\Components\TextInput::make('nome_pai')
                    ->required()
                        ->label('Nome do Pai')
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary'),
                    Forms\Components\TextInput::make('nome_mae')
                    ->required()
                        ->label('Nome da Mãe')
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary'),
                    Forms\Components\TextInput::make('nome_encarregado')
                    ->required()
                        ->label('Nome do Encarregado de Educação')
                        ->suffixIcon('heroicon-o-clipboard-document-list')
                        ->suffixIconColor('primary'),
                    Forms\Components\TextInput::make('telefone_encarregado')
                    ->required()
                        ->label('Telefone do Encarregado de Educação')
                        ->suffixIcon('heroicon-o-phone')
                        ->suffixIconColor('primary')
                        ->tel()
                        ->numeric() // Aceita apenas números
                        ->minLength(9) // Mínimo de 9 dígitos
                        ->mask('999 999 999') // Máscara simples
                ->placeholder('Ex: 912 345 678'),
                ])
                ->columns(3),
                ]);
    }
    private static function getMunicipios(string $provincia): array
    {
        $municipios = [
            'Luanda' => [
                'Belas' => 'Belas',
                'Cacuaco' => 'Cacuaco',
                'Cazenga' => 'Cazenga',
                'Ícolo e Bengo' => 'Ícolo e Bengo',
                'Luanda' => 'Luanda',
                'Quiçama' => 'Quiçama',
                'Viana' => 'Viana',
            ],
            'Benguela' => [
                'Baía Farta' => 'Baía Farta',
                'Balombo' => 'Balombo',
                'Benguela' => 'Benguela',
                'Bocoio' => 'Bocoio',
                'Caimbambo' => 'Caimbambo',
                'Catumbela' => 'Catumbela',
                'Chongoroi' => 'Chongoroi',
                'Cubal' => 'Cubal',
                'Ganda' => 'Ganda',
                'Lobito' => 'Lobito',
            ],
            'Huíla' => [
                'Caconda' => 'Caconda',
                'Caluquembe' => 'Caluquembe',
                'Chiange' => 'Chiange',
                'Chibia' => 'Chibia',
                'Chicomba' => 'Chicomba',
                'Chipindo' => 'Chipindo',
                'Cuvango' => 'Cuvango',
                'Humpata' => 'Humpata',
                'Jamba' => 'Jamba',
                'Lubango' => 'Lubango',
                'Matala' => 'Matala',
                'Quilengues' => 'Quilengues',
                'Quipungo' => 'Quipungo',
            ],
            'Huambo' => [
                'Bailundo' => 'Bailundo',
                'Cachiungo' => 'Cachiungo',
                'Caála' => 'Caála',
                'Ecunha' => 'Ecunha',
                'Huambo' => 'Huambo',
                'Londuimbali' => 'Londuimbali',
                'Longonjo' => 'Longonjo',
                'Mungo' => 'Mungo',
                'Chicala-Choloanga' => 'Chicala-Choloanga',
                'Chinjenje' => 'Chinjenje',
                'Ucuma' => 'Ucuma',
            ],
            'Cabinda' => [
                'Belize' => 'Belize',
                'Buco-Zau' => 'Buco-Zau',
                'Cabinda' => 'Cabinda',
                'Cacongo' => 'Cacongo',
            ],
            'Cunene' => [
                'Cahama' => 'Cahama',
                'Cuanhama' => 'Cuanhama',
                'Curoca' => 'Curoca',
                'Cuvelai' => 'Cuvelai',
                'Namacunde' => 'Namacunde',
                'Ombadja' => 'Ombadja',
            ],
            'Kwanza Norte' => [
                'Ambaca' => 'Ambaca',
                'Banga' => 'Banga',
                'Bolongongo' => 'Bolongongo',
                'Cambambe' => 'Cambambe',
                'Cazengo' => 'Cazengo',
                'Golungo Alto' => 'Golungo Alto',
                'Gonguembo' => 'Gonguembo',
                'Lucala' => 'Lucala',
                'Quiculungo' => 'Quiculungo',
                'Samba Caju' => 'Samba Caju',
            ],
            'Kwanza Sul' => [
                'Amboim' => 'Amboim',
                'Cassongue' => 'Cassongue',
                'Cela' => 'Cela',
                'Conda' => 'Conda',
                'Ebo' => 'Ebo',
                'Libolo' => 'Libolo',
                'Mussende' => 'Mussende',
                'Porto Amboim' => 'Porto Amboim',
                'Quibala' => 'Quibala',
                'Quilenda' => 'Quilenda',
                'Seles' => 'Seles',
                'Sumbe' => 'Sumbe',
            ],
            'Malanje' => [
                'Cacuso' => 'Cacuso',
                'Calandula' => 'Calandula',
                'Cambundi-Catembo' => 'Cambundi-Catembo',
                'Cangandala' => 'Cangandala',
                'Caombo' => 'Caombo',
                'Cuaba Nzogo' => 'Cuaba Nzogo',
                'Cunda-Dia-Baze' => 'Cunda-Dia-Baze',
                'Luquembo' => 'Luquembo',
                'Malanje' => 'Malanje',
                'Marimba' => 'Marimba',
                'Massango' => 'Massango',
                'Mucari' => 'Mucari',
                'Quela' => 'Quela',
                'Quirima' => 'Quirima',
            ],
            'Namibe' => [
                'Bibala' => 'Bibala',
                'Camucuio' => 'Camucuio',
                'Moçâmedes' => 'Moçâmedes',
                'Tômbwa' => 'Tômbwa',
                'Virei' => 'Virei',
            ],
            'Uíge' => [
                'Alto Cauale' => 'Alto Cauale',
                'Ambuíla' => 'Ambuíla',
                'Bembe' => 'Bembe',
                'Buengas' => 'Buengas',
                'Bungo' => 'Bungo',
                'Damba' => 'Damba',
                'Milunga' => 'Milunga',
                'Mucaba' => 'Mucaba',
                'Negage' => 'Negage',
                'Puri' => 'Puri',
                'Quimbele' => 'Quimbele',
                'Quitexe' => 'Quitexe',
                'Sanza Pombo' => 'Sanza Pombo',
                'Songo' => 'Songo',
                'Uíge' => 'Uíge',
                'Zombo' => 'Zombo',
            ],
            'Zaire' => [
                'Cuimba' => 'Cuimba',
                'Mabanza Congo' => 'Mabanza Congo',
                'Nóqui' => 'Nóqui',
                'Nezeto' => 'Nezeto',
                'Soio' => 'Soio',
                'Tomboco' => 'Tomboco',
            ],
            'Lunda Norte' => [
                'Cambulo' => 'Cambulo',
                'Capenda Camulemba' => 'Capenda Camulemba',
                'Caungula' => 'Caungula',
                'Chitato' => 'Chitato',
                'Cuango' => 'Cuango',
                'Cuílo' => 'Cuílo',
                'Lóvua' => 'Lóvua',
                'Lucapa' => 'Lucapa',
                'Xá-Muteba' => 'Xá-Muteba',
            ],
            'Lunda Sul' => [
                'Cacolo' => 'Cacolo',
                'Dala' => 'Dala',
                'Muconda' => 'Muconda',
                'Saurimo' => 'Saurimo',
            ],
            'Moxico' => [
                'Alto Zambeze' => 'Alto Zambeze',
                'Bundas' => 'Bundas',
                'Camanongue' => 'Camanongue',
                'Léua' => 'Léua',
                'Luau' => 'Luau',
                'Luacano' => 'Luacano',
                'Luchazes' => 'Luchazes',
                'Cameia' => 'Cameia',
                'Moxico' => 'Moxico',
            ],
            'Bié' => [
                'Andulo' => 'Andulo',
                'Camacupa' => 'Camacupa',
                'Catabola' => 'Catabola',
                'Chinguar' => 'Chinguar',
                'Chitembo' => 'Chitembo',
                'Cuemba' => 'Cuemba',
                'Cunhinga' => 'Cunhinga',
                'Kuito' => 'Kuito',
                'Nharea' => 'Nharea',
            ],
            'Cuando Cubango' => [
                'Calai' => 'Calai',
                'Cuangar' => 'Cuangar',
                'Cuchi' => 'Cuchi',
                'Cuito Cuanavale' => 'Cuito Cuanavale',
                'Dirico' => 'Dirico',
                'Mavinga' => 'Mavinga',
                'Menongue' => 'Menongue',
                'Nancova' => 'Nancova',
                'Rivungo' => 'Rivungo',
            ],
        ];

        return $municipios[$provincia] ?? [];
    }
    public static function table(Table $table): Table
    {

        return $table
        ->modifyQueryUsing(function ($query) {
            // Filtra os alunos pela escola do usuário logado
            return $query->where('escola_id', auth()->user()->escola_id);
        })
            ->columns([

            TextColumn::make('nome')
                ->label('Nome Completo')
                ->searchable()
                ->sortable(),

                TextColumn::make('numero_processo')
                ->label('Nº do Processo')
                ->searchable()
                ->sortable(),

            TextColumn::make('sexo')
                ->label('Sexo')
                ->searchable()
                ->sortable(),

            TextColumn::make('provincia')
                ->label('Província')
                ->searchable()
                ->sortable(),

            TextColumn::make('municipio')
                ->label('Município')
                ->searchable()
                ->sortable(),

            TextColumn::make('data_emissao')
                ->label('Data de Emissão')
                ->date('d/m/Y') // Formata a data
                ->sortable(),

            TextColumn::make('data_expiracao')
                ->label('Data de Expiração')
                ->date('d/m/Y') // Formata a data
                ->sortable(),

            TextColumn::make('estado_civil')
                ->label('Estado Civil')
                ->searchable()
                ->sortable(),

            TextColumn::make('nome_pai')
                ->label('Nome do Pai')
                ->searchable()
                ->sortable(),

            TextColumn::make('nome_mae')
                ->label('Nome da Mãe')
                ->searchable()
                ->sortable(),

            TextColumn::make('nome_encarregado')
                ->label('Encarregado de Educação')
                ->searchable()
                ->sortable(),

            TextColumn::make('telefone_encarregado')
                ->label('Telefone do Encarregado')
                ->searchable()
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
            'index' => Pages\ListAlunos::route('/'),
            'create' => Pages\CreateAluno::route('/create'),
            'view' => Pages\ViewAluno::route('/{record}'),
            'edit' => Pages\EditAluno::route('/{record}/edit'),
        ];
    }
}
