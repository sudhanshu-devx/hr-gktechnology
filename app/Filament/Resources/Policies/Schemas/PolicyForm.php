<?php

namespace App\Filament\Resources\Policies\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class PolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required(),

            FileUpload::make('file')
                ->label('Policy PDF')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('policies')
                ->disk('public')
                ->required(),
        ]);
    }
}