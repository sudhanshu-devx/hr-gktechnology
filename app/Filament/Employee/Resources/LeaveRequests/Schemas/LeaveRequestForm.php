<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Schemas;

use Carbon\Carbon;
use App\Models\LeaveType;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // =====================
            // User (auto-filled)
            // =====================
            Select::make('user_id')
                ->relationship('user', 'name')
                ->default(auth()->id())
                ->disabled()
                ->dehydrated()
                ->required(),

            // =====================
            // Leave Type
            // =====================
            Select::make('leave_type_id')
                ->relationship('leaveType', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Get $get, Set $set) {

                    $leaveType = LeaveType::find($state);

                    if (! $leaveType) {
                        return;
                    }

                    // Half-day leave → force single day & 0.5
                    if ($leaveType->day_value == 0.5) {
                        $set('end_date', $get('start_date'));
                        $set('days', 0.5);
                    }

                    self::calculateDays($get, $set);
                }),

            // =====================
            // Start Date
            // =====================
            DatePicker::make('start_date')
                ->required()
                ->reactive()
                ->minDate(now()->subDays(7)->toDateString())
                ->afterStateUpdated(function ($state, Get $get, Set $set) {

                    if ($get('end_date') && Carbon::parse($get('end_date'))->lt(Carbon::parse($state))) {
                        $set('end_date', $state);
                    }

                    self::calculateDays($get, $set);
                }),

            // =====================
            // End Date
            // =====================
            DatePicker::make('end_date')
                ->required()
                ->reactive()
                ->minDate(fn (Get $get) => $get('start_date'))
                ->rule(function (Get $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {

                        $start = $get('start_date');
                        $leaveTypeId = $get('leave_type_id');

                        if (! $start || ! $value || ! $leaveTypeId) {
                            return;
                        }

                        $leaveType = LeaveType::find($leaveTypeId);

                        // ✅ Half Day rule
                        if ($leaveType?->day_value == 0.5 && $start !== $value) {
                            $fail('Half-day leave must be for a single day only.');
                            return;
                        }

                        // ✅ Full Day rule (max 12)
                        if ($leaveType?->day_value == 1) {
                            $days = Carbon::parse($start)
                                ->diffInDays(Carbon::parse($value)) + 1;

                            if ($days > 12) {
                                $fail('You can apply for a maximum of 12 days leave.');
                            }
                        }
                    };
                })
                ->afterStateUpdated(fn (Get $get, Set $set) =>
                    self::calculateDays($get, $set)
                ),

            // =====================
            // Days (auto-calculated)
            // =====================
            TextInput::make('days')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->required(),

            // =====================
            // Reason
            // =====================
            Textarea::make('reason')
                ->required()
                ->columnSpanFull(),

            // =====================
            // Status
            // =====================
            Hidden::make('status')
                ->default('pending'),
        ]);
    }

    // =====================
    // Day Calculation Logic
    // =====================
    protected static function calculateDays(Get $get, Set $set): void
    {
        $start = $get('start_date');
        $end = $get('end_date');
        $leaveTypeId = $get('leave_type_id');

        if (! $start || ! $end || ! $leaveTypeId) {
            return;
        }

        $leaveType = LeaveType::find($leaveTypeId);

        // Half-day leave
        if ($leaveType?->day_value == 0.5) {
            $set('days', 0.5);
            return;
        }

        // Full-day leave
        $days = Carbon::parse($start)
            ->diffInDays(Carbon::parse($end)) + 1;

        $set('days', $days);
    }
}
