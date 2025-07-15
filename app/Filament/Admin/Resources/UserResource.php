<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Usuarios';
    
    protected static ?string $modelLabel = 'Usuario';
    
    protected static ?string $pluralModelLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Fecha de Nacimiento'),
                    ])->columns(2),

                Forms\Components\Section::make('Documentación')
                    ->schema([
                        Forms\Components\Select::make('document_type')
                            ->label('Tipo de Documento')
                            ->options([
                                'CC' => 'Cédula de Ciudadanía',
                                'CE' => 'Cédula de Extranjería',
                                'TI' => 'Tarjeta de Identidad',
                                'PP' => 'Pasaporte',
                                'NIT' => 'NIT',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('document_number')
                            ->label('Número de Documento')
                            ->maxLength(50),
                    ])->columns(2),

                Forms\Components\Section::make('Configuración de Cuenta')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Rol')
                            ->options([
                                'admin' => 'Administrador',
                                'office' => 'Oficina',
                                'client' => 'Cliente',
                                'partner' => 'Aliado',
                                'delivery' => 'Repartidor',
                            ])
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verificado')
                            ->displayFormat('d/m/Y H:i'),
                    ])->columns(2),

                Forms\Components\Section::make('Asignaciones')
                    ->schema([
                        Forms\Components\Select::make('zone_id')
                            ->label('Zona')
                            ->relationship('zone', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('franchise_id')
                            ->label('Franquicia')
                            ->relationship('franchise', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Dirección')
                            ->rows(3),

                        Forms\Components\FileUpload::make('profile_image')
                            ->label('Imagen de Perfil')
                            ->image()
                            ->directory('profiles')
                            ->visibility('public'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'office' => 'warning',
                        'partner' => 'info',
                        'delivery' => 'success',
                        'client' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'office' => 'Oficina',
                        'partner' => 'Aliado',
                        'delivery' => 'Repartidor',
                        'client' => 'Cliente',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zona')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('franchise.name')
                    ->label('Franquicia')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Último Acceso')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rol')
                    ->options([
                        'admin' => 'Administrador',
                        'office' => 'Oficina',
                        'client' => 'Cliente',
                        'partner' => 'Aliado',
                        'delivery' => 'Repartidor',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->boolean()
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos')
                    ->native(false),

                Tables\Filters\SelectFilter::make('zone_id')
                    ->label('Zona')
                    ->relationship('zone', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('franchise_id')
                    ->label('Franquicia')
                    ->relationship('franchise', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
