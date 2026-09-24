<?php

namespace App\Filament\Hr\Resources\OfferLetters\Pages;

use App\Filament\Hr\Resources\OfferLetters\OfferLetterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfferLetters extends ListRecords
{
    protected static string $resource = OfferLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
