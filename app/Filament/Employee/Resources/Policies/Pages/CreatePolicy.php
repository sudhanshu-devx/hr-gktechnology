<?php

namespace App\Filament\Employee\Resources\Policies\Pages;

use App\Filament\Employee\Resources\Policies\PolicyResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePolicy extends CreateRecord
{
    protected static string $resource = PolicyResource::class;
}
