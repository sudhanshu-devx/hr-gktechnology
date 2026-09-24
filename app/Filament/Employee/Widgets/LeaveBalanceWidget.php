<?php

namespace App\Filament\Employee\Widgets;

use App\Models\LeaveType;
use App\Models\LeaveRequest;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LeaveBalanceWidget extends BaseWidget
{
    protected static ?string $heading = 'My Leave Balance';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userId = auth()->id();
        $year = now()->year;

        return $table
            ->query(
                LeaveType::query()
                    ->whereNull('deducts_from_leave_type_id') // hide Half Day
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Leave Type'),

                TextColumn::make('days_per_year')
                    ->label('Allowed')
                    ->formatStateUsing(fn ($state) => $state ?? '∞'),

                TextColumn::make('used')
                    ->label('Used')
                    ->getStateUsing(function (LeaveType $record) use ($userId, $year) {

                        return LeaveRequest::query()
                            ->where('user_id', $userId)
                            ->whereYear('start_date', $year)
                            ->where('status', 'approved')
                            ->where(function ($q) use ($record) {
                                $q->where('leave_type_id', $record->id)
                                  ->orWhereIn('leave_type_id', function ($sub) use ($record) {
                                      $sub->select('id')
                                          ->from('leave_types')
                                          ->where('deducts_from_leave_type_id', $record->id);
                                  });
                            })
                            ->sum('days');
                    }),

                TextColumn::make('remaining')
                    ->label('Remaining')
                    ->getStateUsing(function (LeaveType $record) use ($userId, $year) {

                        if ($record->days_per_year === null) {
                            return '∞';
                        }

                        $used = LeaveRequest::query()
                            ->where('user_id', $userId)
                            ->whereYear('start_date', $year)
                            ->where('status', 'approved')
                            ->where(function ($q) use ($record) {
                                $q->where('leave_type_id', $record->id)
                                  ->orWhereIn('leave_type_id', function ($sub) use ($record) {
                                      $sub->select('id')
                                          ->from('leave_types')
                                          ->where('deducts_from_leave_type_id', $record->id);
                                  });
                            })
                            ->sum('days');

                        return max(0, $record->days_per_year - $used);
                    }),
            ])
            ->paginated(false);
    }
    public static function canView(): bool
{
    return request()->routeIs(
        'filament.employee.resources.leave-requests.index'
    );
}

}
