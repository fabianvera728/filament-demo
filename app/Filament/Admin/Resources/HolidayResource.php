<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HolidayResource\Pages;
use App\Filament\Admin\Resources\HolidayResource\RelationManagers;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\Toggle::make('affects_delivery')
                    ->required(),
                Forms\Components\Toggle::make('affects_operations')
                    ->required(),
                Forms\Components\Textarea::make('affected_zones')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('affected_franchises')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('alternative_opening_time'),
                Forms\Components\TextInput::make('alternative_closing_time'),
                Forms\Components\TextInput::make('delivery_surcharge')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_recurring')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
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
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\IconColumn::make('affects_delivery')
                    ->boolean(),
                Tables\Columns\IconColumn::make('affects_operations')
                    ->boolean(),
                Tables\Columns\TextColumn::make('alternative_opening_time'),
                Tables\Columns\TextColumn::make('alternative_closing_time'),
                Tables\Columns\TextColumn::make('delivery_surcharge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
