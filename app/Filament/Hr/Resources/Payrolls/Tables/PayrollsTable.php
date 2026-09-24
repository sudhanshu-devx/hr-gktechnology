<?php

namespace App\Filament\Hr\Resources\Payrolls\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Jobs\GeneratePayrollJob;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table

        /* =======================
           HEADER ACTION
           ======================= */
        ->headerActions([
            Action::make("generate_payroll")
                ->label("Generate Payroll")
                ->icon("heroicon-o-cog")
                ->color("success")
                ->schema([

                    Select::make('month')
                        ->options([
                            1=>'January',2=>'February',3=>'March',4=>'April',
                            5=>'May',6=>'June',7=>'July',8=>'August',
                            9=>'September',10=>'October',11=>'November',12=>'December',
                        ])
                        ->default(now()->month)
                        ->required(),

                    TextInput::make('year')
                        ->required()
                        ->numeric()
                        ->default(now()->year),

                    Select::make('user_id')
                        ->label('Employee (Optional)')
                        ->placeholder('Generate for all employees')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload(),
                ])
                ->action(function(array $data){

                    GeneratePayrollJob::dispatch(
                        $data['month'],
                        $data['year'],
                        $data['user_id'] ?? null
                    );

                    Notification::make()
                        ->success()
                        ->title('Payroll Generation Started')
                        ->body('Payroll is being generated in the background.')
                        ->send();
                })
        ])

        /* =======================
           TABLE COLUMNS
           ======================= */
        ->columns([

            TextColumn::make('user.name')
                ->label('Employee')
                ->searchable(),

            TextColumn::make('user.employee_id')
                ->label('Employee Code')
                ->searchable(),

            // ✅ FIXED (NO CARBON ERROR)
            TextColumn::make('month')
                ->label('Month'),

            TextColumn::make('year')
                ->sortable(),

            TextColumn::make('basic_salary')
                ->money('INR', true)
                ->label('Basic'),

            TextColumn::make('hra')
                ->money('INR', true)
                ->label('HRA')
                ->toggleable(),

            TextColumn::make('special_allowance')
                ->money('INR', true)
                ->label('Special')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('other_allowance')
                ->money('INR', true)
                ->label('Other')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('bonus')
                ->money('INR', true)
                ->label('Bonus')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('deductions')
                ->money('INR', true)
                ->label('Deductions'),

            TextColumn::make('net_salary')
                ->money('INR', true)
                ->label('Net Pay')
                ->sortable(),

            TextColumn::make('status')
                ->badge(),

            TextColumn::make('created_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ])

        /* =======================
           ROW ACTIONS
           ======================= */
        ->actions([

            Action::make('download')
                ->label('Download Payslip')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->url(fn ($record) => route('payslip.download', $record))
                ->openUrlInNewTab(),
        ])

        /* =======================
           BULK ACTIONS
           ======================= */
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}