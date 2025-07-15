<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StatusHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistory';

    protected static ?string $title = 'Historial de Estados';

    protected static ?string $modelLabel = 'Cambio de Estado';

    protected static ?string $pluralModelLabel = 'Historial de Estados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options(Order::getStatuses())
                    ->required()
                    ->disabled(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->disabled(),

                Forms\Components\Select::make('changed_by')
                    ->label('Cambiado por')
                    ->relationship('changedBy', 'name')
                    ->disabled(),

                Forms\Components\DateTimePicker::make('changed_at')
                    ->label('Fecha del cambio')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_CONFIRMED => 'info',
                        Order::STATUS_IN_PROCESS => 'primary',
                        Order::STATUS_READY => 'success',
                        Order::STATUS_OUT_FOR_DELIVERY => 'info',
                        Order::STATUS_DELIVERED => 'success',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('changedBy.name')
                    ->label('Cambiado por')
                    ->default('Sistema')
                    ->sortable(),

                Tables\Columns\TextColumn::make('changed_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($state): string => $state?->format('l, j F Y \a \l\a\s H:i:s') ?? ''),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Order::getStatuses())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('changed_by')
                    ->label('Usuario')
                    ->relationship('changedBy', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                // No permitir crear registros manuales en el historial
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No permitir acciones masivas en el historial
            ])
            ->defaultSort('changed_at', 'desc')
            ->paginated([5, 10, 25])
            ->poll('60s'); // Actualizar cada minuto para cambios en tiempo real
    }

    public function isReadOnly(): bool
    {
        return true; // El historial debe ser solo lectura
    }
} 