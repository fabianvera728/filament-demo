<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BonusResource\Pages;
use App\Filament\Admin\Resources\BonusResource\RelationManagers;
use App\Models\Bonus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BonusResource extends Resource
{
    protected static ?string $model = Bonus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('source')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('used_value')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('remaining_value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('currency')
                    ->required(),
                Forms\Components\TextInput::make('source_order_id')
                    ->numeric(),
                Forms\Components\TextInput::make('source_user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('source_reference'),
                Forms\Components\TextInput::make('minimum_order_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('applicable_products')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('applicable_categories')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('applicable_franchises')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('excluded_products')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('earned_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at'),
                Forms\Components\DateTimePicker::make('used_at'),
                Forms\Components\DateTimePicker::make('cancelled_at'),
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('terms_conditions')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_transferable')
                    ->required(),
                Forms\Components\Toggle::make('is_combinable')
                    ->required(),
                Forms\Components\Textarea::make('usage_history')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_data')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('cancellation_reason')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source_order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('minimum_order_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('earned_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_transferable')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_combinable')
                    ->boolean(),
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
            'index' => Pages\ListBonuses::route('/'),
            'create' => Pages\CreateBonus::route('/create'),
            'edit' => Pages\EditBonus::route('/{record}/edit'),
        ];
    }
}
