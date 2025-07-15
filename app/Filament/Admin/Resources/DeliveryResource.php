<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DeliveryResource\Pages;
use App\Filament\Admin\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('delivery_number')
                    ->required(),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Forms\Components\Select::make('delivery_person_id')
                    ->relationship('deliveryPerson', 'name'),
                Forms\Components\Select::make('zone_id')
                    ->relationship('zone', 'name'),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('delivery_type')
                    ->required(),
                Forms\Components\TextInput::make('delivery_address')
                    ->required(),
                Forms\Components\TextInput::make('delivery_city')
                    ->required(),
                Forms\Components\TextInput::make('delivery_state'),
                Forms\Components\TextInput::make('delivery_postal_code'),
                Forms\Components\TextInput::make('delivery_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('delivery_longitude')
                    ->numeric(),
                Forms\Components\Textarea::make('delivery_instructions')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('recipient_name')
                    ->required(),
                Forms\Components\TextInput::make('recipient_phone')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('recipient_email')
                    ->email(),
                Forms\Components\DateTimePicker::make('assigned_at'),
                Forms\Components\DateTimePicker::make('picked_up_at'),
                Forms\Components\DateTimePicker::make('in_transit_at'),
                Forms\Components\DateTimePicker::make('delivered_at'),
                Forms\Components\DateTimePicker::make('failed_at'),
                Forms\Components\DateTimePicker::make('cancelled_at'),
                Forms\Components\DateTimePicker::make('estimated_delivery_time'),
                Forms\Components\DateTimePicker::make('scheduled_delivery_time'),
                Forms\Components\TextInput::make('delivery_fee')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tip_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('distance_km')
                    ->numeric(),
                Forms\Components\TextInput::make('estimated_duration_minutes')
                    ->numeric(),
                Forms\Components\Textarea::make('delivery_notes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('failure_reason')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('cancellation_reason')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('proof_of_delivery'),
                Forms\Components\TextInput::make('current_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('current_longitude')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('last_location_update'),
                Forms\Components\Textarea::make('route_data')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_data')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('delivery_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deliveryPerson.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('picked_up_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('in_transit_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('failed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_delivery_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_delivery_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_fee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tip_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('distance_km')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_duration_minutes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proof_of_delivery')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_location_update')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
