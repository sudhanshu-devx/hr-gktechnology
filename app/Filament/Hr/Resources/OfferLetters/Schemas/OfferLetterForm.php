<?php

namespace App\Filament\Hr\Resources\OfferLetters\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class OfferLetterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 👤 Employee Selection
                Select::make('user_id')
                    ->label('Select Existing Employee')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->live(),

                // ✍️ Manual Name
                TextInput::make('employee_name')
                    ->label('Employee Name (if not in system)')
                    ->maxLength(255)
                    ->required(fn ($get) => !$get('user_id')),

                // 💼 Position
                TextInput::make('position')
                    ->required(),

                // 🧾 Employment Type
                Select::make('employment_type')
                    ->options([
                        'intern' => 'Intern',
                        'employee' => 'Employee',
                    ])
                    ->live()
                    ->required(),

                // 💰 Salary (Employee only)
                TextInput::make('salary')
                    ->label('Total Salary')
                    ->numeric()
                    ->prefix('₹')
                    ->visible(fn ($get) => $get('employment_type') === 'employee')
                    ->required(fn ($get) => $get('employment_type') === 'employee')
                    ->live(),

                // 💸 Stipend (Intern only)
                TextInput::make('stipend')
                    ->numeric()
                    ->prefix('₹')
                    ->visible(fn ($get) => $get('employment_type') === 'intern')
                    ->required(fn ($get) => $get('employment_type') === 'intern'),

                // 📊 Salary Breakdown (Employee only)
                Section::make('Salary Breakdown')
                    ->visible(fn ($get) => $get('employment_type') === 'employee')
                    ->schema([

                        TextInput::make('basic_salary')
                            ->label('Basic Salary')
                            ->numeric()
                            ->prefix('₹')
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::updateTotal($set, $get)
                            ),

                        TextInput::make('hra')
                            ->label('HRA')
                            ->numeric()
                            ->prefix('₹')
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::updateTotal($set, $get)
                            ),

                        TextInput::make('special_allowance')
                            ->label('Special Allowance')
                            ->numeric()
                            ->prefix('₹')
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::updateTotal($set, $get)
                            ),

                        TextInput::make('bonus')
                            ->label('Bonus')
                            ->numeric()
                            ->prefix('₹')
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::updateTotal($set, $get)
                            ),

                        TextInput::make('pf_deduction')
                            ->label('PF Deduction')
                            ->numeric()
                            ->prefix('₹'),

                        TextInput::make('tax_deduction')
                            ->label('Tax Deduction')
                            ->numeric()
                            ->prefix('₹'),

                    ])
                    ->columns(2),

                // 📅 Start Date
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                // 📍 Location
                Select::make('location')
                    ->options([
                        'Prayagraj' => 'Prayagraj',
                        // 'Lucknow' => 'Lucknow',
                    ])
                    ->required(),
            ]);
    }

    /**
     * 🔥 Auto Calculate Total Salary
     */
    protected static function updateTotal($set, $get): void
    {
        $total =
            ($get('basic_salary') ?? 0)
            + ($get('hra') ?? 0)
            + ($get('special_allowance') ?? 0)
            + ($get('bonus') ?? 0);

        $set('salary', $total);
    }
}