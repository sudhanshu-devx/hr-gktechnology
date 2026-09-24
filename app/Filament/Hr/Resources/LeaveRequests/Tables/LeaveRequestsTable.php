<?php

namespace App\Filament\Hr\Resources\LeaveRequests\Tables;

use Filament\Tables\Table;
use App\Models\LeaveRequest;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 🔥 Only show EMPLOYEE leave requests (not HR's own)
            ->modifyQueryUsing(fn ($query) =>
                $query->where('user_id', '!=', Auth::id())
            )

            ->columns([
                TextColumn::make('user.name')
                    ->label('Employee')
                    ->searchable(),

                TextColumn::make('leaveType.name')
                    ->label('Leave type')
                    ->searchable(),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('days')
    ->label('Days')
    ->formatStateUsing(fn ($state) => number_format((float) $state, 1))
    ->sortable(),


                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('approver.name')
                    ->label('Approved by')
                    ->sortable(),

                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])

            ->recordActions([

                // HR should NOT edit employee leave requests
                EditAction::make()
                    ->visible(false),

                // ✅ APPROVE (only pending + not own leave)
                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (LeaveRequest $record) =>
                        $record->status === 'pending'
                        && $record->user_id !== Auth::id()
                    )
                    ->action(function (LeaveRequest $record) {
                        $record->update([
                            'status'       => 'approved',
                            'approved_by'  => Auth::id(),
                            'approved_at'  => now(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Leave approved')
                            ->send();
                    }),

                // ❌ REJECT (only pending + not own leave)
                Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (LeaveRequest $record) =>
                        $record->status === 'pending'
                        && $record->user_id !== Auth::id()
                    )
                    ->schema([
                        Textarea::make('rejection_reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (LeaveRequest $record, array $data) {
                        $record->update([
                            'status'            => 'rejected',
                            'approved_by'       => Auth::id(),
                            'approved_at'       => now(),
                            'rejection_reason'  => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Leave rejected')
                            ->send();
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(false), // 🔒 HR should not bulk delete
                ]),
            ]);
    }
}
