<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Leave Information')
                ->columns(2)
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->disabled()
                        ->dehydrated(false)
                        ->label('HR User'),

                    Select::make('leave_type_id')
                        ->relationship('leaveType', 'name')
                        ->disabled()
                        ->dehydrated(false),

                    DatePicker::make('start_date')
                        ->disabled()
                        ->dehydrated(false),

                    DatePicker::make('end_date')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('days')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('status')
                        ->disabled()
                        ->dehydrated(false),

                    DateTimePicker::make('approved_at')
                        ->disabled()
                        ->dehydrated(false),
                ]),

            Section::make('Reason & Notes')
                ->schema([
                    Textarea::make('reason')
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),

                    Textarea::make('rejection_reason')
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
