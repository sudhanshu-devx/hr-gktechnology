<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Grid;

class UserForm
{
    public static function configure(Schema $schema, bool $readOnly = false): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'md' => 10,
                'xl' => 20,
            ])->schema([

                /* =======================
                   PERSONAL INFORMATION
                   ======================= */
                Section::make('Personal Informations')
                    ->columnSpan([
                        'default' => 1,
                        'md' => 10,
                        'xl' => 20,
                    ])
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->disabled($readOnly),

                        TextInput::make('email')
                            ->label('Company Email address')
                            ->email()
                            ->required()
                            ->disabled($readOnly),

                        TextInput::make('alias_name')
                            ->label('Alias Name')
                            ->disabled($readOnly),

                        TextInput::make('company_alias_email')
                            ->label('Personal Email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->disabled($readOnly),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->visible(! $readOnly),

                        TextInput::make('phone')
                            ->tel()
                            ->disabled($readOnly),

                        DatePicker::make('date_of_birth')
                            ->disabled($readOnly),

                        Textarea::make('address')
                            ->columnSpanFull()
                            ->disabled($readOnly),

                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->visible(! $readOnly),
                    ]),

                /* =======================
                   EMPLOYMENT DETAILS
                   ======================= */
                Section::make('Employment Details')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('employee_id')
                            ->label('Employee Code')
                            ->readOnly()
                            ->unique(ignoreRecord: true)
                            ->hiddenOn('create'),

                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->disabled($readOnly),

                        Select::make('position_id')
                            ->relationship(
                                'position',
                                'title',
                                fn ($query, Get $get) =>
                                    $query->where('department_id', $get('department_id'))
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled($readOnly),

                    //     DatePicker::make('hire_date')
                    //         ->required()
                    //         ->disabled($readOnly),
                            
                    //         Select::make('location')
                    //             ->options([
                    //                 'Prayagraj' => 'Prayagraj',
                    //                   'Lucknow'   => 'Lucknow',
                    //              ])
                    //  ->required()
                    //          ->native(false),

                    //     ToggleButtons::make('employment_type')
                    //         ->options([
                    //             'full-time' => 'Full time',
                    //             'part-time' => 'Part time',
                    //             'contract' => 'Contract',
                    //             'intern' => 'Intern',
                    //         ])
                    DatePicker::make('hire_date')
    ->required()
    ->disabled($readOnly),

TextInput::make('location')
    ->default('Prayagraj')
    ->readOnly()
    ->required(),

ToggleButtons::make('employment_type')
    ->options([
        'full-time' => 'Full time',
        'part-time' => 'Part time',
        'contract' => 'Contract',
        'intern' => 'Intern',
    ])
                            ->colors([
                                'full-time' => 'success',
                                'part-time' => 'warning',
                                'contract' => 'danger',
                                'intern' => 'info',
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->default('full-time')
                            ->required()
                            ->disabled($readOnly),

                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'on-leave' => 'On leave',
                                'terminated' => 'Terminated',
                            ])
                            ->default('active')
                            ->required()
                            ->visible(! $readOnly),

                        TextInput::make('salary')
                            ->numeric()
                            ->disabled($readOnly),
                    ]),

                /* =======================
                   KYC DETAILS
                   ======================= */
                Section::make('KYC Details')
    ->relationship('kyc')
    ->columnSpanFull()
    ->columns(2)
    ->schema([
        TextInput::make('aadhaar_number')
            ->label('Aadhaar Number')
            ->numeric()
            ->length(12)
            ->placeholder('12 digit Aadhaar')
            ->nullable()
            ->disabled($readOnly),

        TextInput::make('pan_number')
            ->label('PAN Number')
            ->maxLength(10)
            ->placeholder('ABCDE1234F')
            ->nullable()
            ->disabled($readOnly),
    ]),


                /* =======================
                   BANK DETAILS
                   ======================= */
                Section::make('Bank Details')
    ->relationship('bank')
    ->columnSpanFull()
    ->columns(2)
    ->schema([
        TextInput::make('bank_name')
            ->label('Bank Name')
            ->nullable()
            ->disabled($readOnly),

        TextInput::make('account_number')
            ->label('Account Number')
            ->nullable()
            ->disabled($readOnly),

        TextInput::make('ifsc_code')
            ->label('IFSC Code')
            ->maxLength(11)
            ->nullable()
            ->disabled($readOnly),

        TextInput::make('branch_name')
            ->label('Branch Name')
            ->nullable()
            ->disabled($readOnly),
    ]),


                /* =======================
                   EMERGENCY CONTACT
                   ======================= */
                Section::make('Emergency Contact')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('emergency_contact_name')
                            ->label('Emergency contact name')
                            ->disabled($readOnly),

                        TextInput::make('emergency_contact_phone')
                            ->label('Emergency contact phone')
                            ->tel()
                            ->disabled($readOnly),
                    ]),
            ]),
        ]);
    }
}
