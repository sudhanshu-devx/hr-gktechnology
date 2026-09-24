<?php

namespace App\Filament\Hr\Resources\OfferLetters\Pages;

use App\Filament\Hr\Resources\OfferLetters\OfferLetterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOfferLetter extends EditRecord
{
    protected static string $resource = OfferLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
