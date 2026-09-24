<?php

namespace App\Filament\Hr\Resources\Policies\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;


class PoliciesTable
{
    public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('title')->searchable(),

            TextColumn::make('file')
                ->label('Download')
                ->formatStateUsing(fn () => 'Download PDF')
                ->url(fn ($record) => asset('laravel/public/storage/' . $record->file))
                ->openUrlInNewTab(),

            TextColumn::make('created_at')->date(),
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

    EditAction::make(),
    DeleteAction::make(),
])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(), // ✅ MULTI DELETE
            ]),
        ]);

    }
}