<?php

namespace App\Filament\Hr\Resources\OfferLetters\Tables;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;


class OfferLettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Show employee name instead of ID
                TextColumn::make('employee')
    ->label('Employee')
    ->getStateUsing(fn ($record) => $record->user?->name ?? $record->employee_name)
    ->searchable()
    ->sortable(),

                TextColumn::make('position')
                    ->searchable(),

                TextColumn::make('employment_type')
                    ->badge(),

                TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('stipend')
                    ->numeric()
                    ->sortable(),


                TextColumn::make('location')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])

           ->recordActions([

    EditAction::make(),
                // 🔥 DOWNLOAD DROPDOWN
                ActionGroup::make([

                    Action::make('download_gk')
                        ->label('Download GK Format')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record): StreamedResponse {

                            $pdf = Pdf::loadView('pdf.offer-letter-gk', [
                                'offer' => $record,
                            ]);

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'GK_Offer_Letter_' . $record->id . '.pdf'
                            );
                        }),

                    Action::make('download_espirits')
                        ->label('Download E-Spirits Format')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record): StreamedResponse {

                            $pdf = Pdf::loadView('pdf.offer-letter-espirits', [
                                'offer' => $record,
                            ]);

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'Espirits_Offer_Letter_' . $record->id . '.pdf'
                            );
                        }),

                ])
                ->label('Download')
                ->icon('heroicon-o-chevron-down'),

            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}