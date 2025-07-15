<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payment_number')
                    ->required(),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('gateway')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fee_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('net_amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('currency')
                    ->required(),
                Forms\Components\TextInput::make('gateway_transaction_id'),
                Forms\Components\TextInput::make('gateway_reference'),
                Forms\Components\TextInput::make('gateway_status'),
                Forms\Components\Textarea::make('gateway_response')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('gateway_data')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('card_last_four'),
                Forms\Components\TextInput::make('card_brand'),
                Forms\Components\TextInput::make('card_type'),
                Forms\Components\TextInput::make('card_holder_name'),
                Forms\Components\DateTimePicker::make('processed_at'),
                Forms\Components\DateTimePicker::make('completed_at'),
                Forms\Components\DateTimePicker::make('failed_at'),
                Forms\Components\DateTimePicker::make('refunded_at'),
                Forms\Components\Textarea::make('failure_reason')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('receipt_url'),
                Forms\Components\Textarea::make('metadata')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_test')
                    ->required(),
                Forms\Components\TextInput::make('refunded_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('refund_reference'),
                Forms\Components\Textarea::make('refund_reason')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gateway')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fee_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gateway_transaction_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gateway_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gateway_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_last_four')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_holder_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('processed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('failed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('refunded_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receipt_url')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_test')
                    ->boolean(),
                Tables\Columns\TextColumn::make('refunded_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('refund_reference')
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
