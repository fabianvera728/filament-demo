<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PqrResource\Pages;
use App\Filament\Admin\Resources\PqrResource\RelationManagers;
use App\Models\Pqr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PqrResource extends Resource
{
    protected static ?string $model = Pqr::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ticket_number')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('priority')
                    ->required(),
                Forms\Components\TextInput::make('category'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('customer_name')
                    ->required(),
                Forms\Components\TextInput::make('customer_email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('customer_phone')
                    ->tel(),
                Forms\Components\TextInput::make('customer_document'),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id'),
                Forms\Components\TextInput::make('product_id')
                    ->numeric(),
                Forms\Components\TextInput::make('franchise_id')
                    ->numeric(),
                Forms\Components\TextInput::make('assigned_to')
                    ->numeric(),
                Forms\Components\Textarea::make('attachments')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('assigned_at'),
                Forms\Components\DateTimePicker::make('first_response_at'),
                Forms\Components\DateTimePicker::make('resolved_at'),
                Forms\Components\DateTimePicker::make('closed_at'),
                Forms\Components\DateTimePicker::make('escalated_at'),
                Forms\Components\DateTimePicker::make('due_date'),
                Forms\Components\Textarea::make('resolution')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('internal_notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('satisfaction_rating')
                    ->numeric(),
                Forms\Components\Textarea::make('satisfaction_feedback')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('tags')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_data')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_document')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('franchise_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_response_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resolved_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('closed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('escalated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satisfaction_rating')
                    ->numeric()
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
            'index' => Pages\ListPqrs::route('/'),
            'create' => Pages\CreatePqr::route('/create'),
            'edit' => Pages\EditPqr::route('/{record}/edit'),
        ];
    }
}
