<?php

namespace App\Filament\Hr\Resources\OfferLetters\Pages;

use App\Filament\Hr\Resources\OfferLetters\OfferLetterResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOfferLetter extends CreateRecord
{
    protected static string $resource = OfferLetterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['generated_by'] = Auth::id();

        return $data;
    }
}