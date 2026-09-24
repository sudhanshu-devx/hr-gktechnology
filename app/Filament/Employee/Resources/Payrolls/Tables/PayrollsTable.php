<?php

namespace App\Filament\Employee\Resources\Payrolls\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 🔐 Employee sees only own payrolls
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('user_id', auth()->id())
            )

            ->columns([
                TextColumn::make('month')
                    ->label('Month'),

                TextColumn::make('year')
                    ->sortable(),

                TextColumn::make('basic_salary')
                    ->money('INR', true),

                TextColumn::make('allowances')
                    ->money('INR', true),

                TextColumn::make('deductions')
                    ->money('INR', true),

                TextColumn::make('bonus')
                    ->money('INR', true),

                TextColumn::make('net_salary')
                    ->money('INR', true)
                    ->weight('bold'),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('paid_at')
                    ->date(),
            ])

            ->actions([
    Action::make('download')
        ->label('Download Payslip')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->url(fn ($record) => route('payslip.download', $record))
        ->openUrlInNewTab()
        ->visible(fn ($record) => $record->status === 'paid'),
]);
    }
}
