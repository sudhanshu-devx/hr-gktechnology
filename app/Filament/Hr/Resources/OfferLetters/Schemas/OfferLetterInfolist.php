<?php

namespace App\Filament\Hr\Resources\OfferLetters\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OfferLetterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('position'),
                TextEntry::make('employment_type')
                    ->badge(),
                TextEntry::make('salary')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('stipend')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('duration')
                    ->placeholder('-'),
                TextEntry::make('location')
                    ->badge(),
                TextEntry::make('pdf_path')
                    ->placeholder('-'),
                TextEntry::make('generated_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
