<?php

namespace App\Filament\Employee\Resources\Policies\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PoliciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('file')
                    ->label('Download')
                    ->formatStateUsing(fn () => 'Download PDF')
                    ->url(fn ($record) => asset('laravel/public/storage/' . $record->file))
                    ->openUrlInNewTab(),

                TextColumn::make('created_at')
                    ->date(),
                    
            ])
            ->actions([
    Action::make('preview')
        ->label('Preview')
        ->icon('heroicon-o-eye')
        ->modalHeading('Policy Preview')
        ->modalContent(fn ($record) => view('filament.preview-pdf', [
            'url' => asset('laravel/public/storage/' . $record->file),
        ]))
        ->modalSubmitAction(false),
            ]);
    }
}