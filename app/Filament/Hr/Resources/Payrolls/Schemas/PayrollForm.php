<?php

namespace App\Filament\Hr\Resources\Payrolls\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* =======================
               EMPLOYEE
               ======================= */
            Select::make('user_id')
                ->label('Employee')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->live()
                ->required()
                ->afterStateUpdated(function ($state, Set $set) {
                    $user = User::find($state);
                    if ($user) {
                        $set('basic_salary', $user->salary);
                    }
                }),

            /* =======================
               PAYROLL PERIOD
               ======================= */
            Select::make('month')
                ->label('Payroll Month')
                ->options([
                    1=>'January',2=>'February',3=>'March',4=>'April',
                    5=>'May',6=>'June',7=>'July',8=>'August',
                    9=>'September',10=>'October',11=>'November',12=>'December',
                ])
                ->required(),

            TextInput::make('year')
                ->label('Payroll Year')
                ->numeric()
                ->default(date('Y'))
                ->required(),

            /* =======================
               SALARY STRUCTURE
               ======================= */
            TextInput::make('basic_salary')
                ->label('Basic Salary')
                ->numeric()
                ->prefix('₹')
                ->required()
                ->live()
                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                    self::calculateNetSalary($set, $get)
                ),

            /* =======================
               ALLOWANCES
               ======================= */
            TextInput::make('hra')
                ->label('HRA')
                ->numeric()
                ->default(0)
                ->prefix('₹')
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                    self::calculateNetSalary($set, $get)
                ),

            TextInput::make('special_allowance')
                ->label('Special Allowance')
                ->numeric()
                ->default(0)
                ->prefix('₹')
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                    self::calculateNetSalary($set, $get)
                ),

            TextInput::make('other_allowance')
                ->label('Other Allowances') // ✅ NEW FIELD
                ->numeric()
                ->default(0)
                ->prefix('₹')
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                    self::calculateNetSalary($set, $get)
                ),

            TextInput::make('bonus')
                ->label('Bonus')
                ->numeric()
                ->default(0)
                ->prefix('₹')
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                    self::calculateNetSalary($set, $get)
                ),

            /* =======================
               DEDUCTIONS
               ======================= */
            TextInput::make('deductions')
                ->label('Total Deductions')
                ->numeric()
                ->default(0)
                ->prefix('₹')
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                    self::calculateNetSalary($set, $get)
                ),

            /* =======================
               NET SALARY
               ======================= */
            TextInput::make('net_salary')
                ->label('Net Pay')
                ->numeric()
                ->disabled()
                ->prefix('₹')
                ->dehydrated()
                ->required(),

            /* =======================
               STATUS
               ======================= */
            Select::make('status')
                ->label('Payroll Status')
                ->options([
                    'draft'     => 'Draft',
                    'processed' => 'Processed',
                    'paid'      => 'Paid',
                ])
                ->default('draft')
                ->required(),

            DatePicker::make('paid_at')
                ->label('Payment Date'),
        ]);
    }

    /* =======================
       NET SALARY CALCULATION
       ======================= */
    protected static function calculateNetSalary(Set $set, Get $get): void
    {
        $basic            = (float) ($get('basic_salary') ?? 0);
        $hra              = (float) ($get('allowances') ?? 0);
        $specialAllowance = (float) ($get('special_allowance') ?? 0);
        $otherAllowance   = (float) ($get('other_allowance') ?? 0); // ✅ NEW
        $bonus            = (float) ($get('bonus') ?? 0);
        $deductions       = (float) ($get('deductions') ?? 0);

        $netSalary = $basic 
                   + $hra 
                   + $specialAllowance 
                   + $otherAllowance 
                   + $bonus 
                   - $deductions;

        $set('net_salary', $netSalary);
    }
}