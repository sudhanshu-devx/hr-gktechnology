<?php

namespace App\Filament\Hr\Resources\Policies\Pages;

use App\Filament\Hr\Resources\Policies\PolicyResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePolicy extends CreateRecord
{
    protected static string $resource = PolicyResource::class;
}
