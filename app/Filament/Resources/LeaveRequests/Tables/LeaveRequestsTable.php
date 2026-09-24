<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use App\Models\LeaveRequest;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('HR Name')
                    ->searchable(),

                TextColumn::make('leaveType.name')
                    ->label('Leave Type'),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('days')
                    ->numeric(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                TextColumn::make('approver.name')
                    ->label('Approved By'),

                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->disabled(), // view-only

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (LeaveRequest $record) =>
                        $record->status === 'pending'
                        && Auth::user()->hasAnyRole(['admin', 'super_admin'])
                    )
                    ->action(function (LeaveRequest $record) {
                        $record->update([
                            'status'       => 'approved',
                            'approved_by'  => Auth::id(),
                            'approved_at'  => now(),
                        ]);

                        Notification::make()
                            ->title('Leave approved')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (LeaveRequest $record) =>
                        $record->status === 'pending'
                        && Auth::user()->hasAnyRole(['admin', 'super_admin'])
                    )
                    ->form([
                        \Filament\Forms\Components\Textarea::make('rejection_reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (LeaveRequest $record, array $data) {
                        $record->update([
                            'status'            => 'rejected',
                            'approved_by'       => Auth::id(),
                            'approved_at'       => now(),
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Leave rejected')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
