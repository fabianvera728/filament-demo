<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CampaignResource\Pages;
use App\Filament\Admin\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('discount_type')
                    ->required(),
                Forms\Components\TextInput::make('discount_value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('minimum_order_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('maximum_discount_amount')
                    ->numeric(),
                Forms\Components\TextInput::make('usage_limit')
                    ->numeric(),
                Forms\Components\TextInput::make('usage_limit_per_customer')
                    ->numeric(),
                Forms\Components\TextInput::make('used_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('ends_at')
                    ->required(),
                Forms\Components\Textarea::make('applicable_products')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('applicable_categories')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('applicable_zones')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('applicable_franchises')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('applicable_user_roles')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('excluded_products')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('excluded_categories')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Toggle::make('is_public')
                    ->required(),
                Forms\Components\Toggle::make('requires_code')
                    ->required(),
                Forms\Components\Toggle::make('stackable')
                    ->required(),
                Forms\Components\TextInput::make('priority')
                    ->required(),
                Forms\Components\Textarea::make('conditions')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_data')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimum_order_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maximum_discount_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit_per_customer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
                Tables\Columns\IconColumn::make('requires_code')
                    ->boolean(),
                Tables\Columns\IconColumn::make('stackable')
                    ->boolean(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
