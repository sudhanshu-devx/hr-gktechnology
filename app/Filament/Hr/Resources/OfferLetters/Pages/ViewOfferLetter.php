<?php

namespace App\Filament\Hr\Resources\OfferLetters\Pages;

use App\Filament\Hr\Resources\OfferLetters\OfferLetterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOfferLetter extends ViewRecord
{
    protected static string $resource = OfferLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
