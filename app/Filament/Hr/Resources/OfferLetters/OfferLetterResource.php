<?php

namespace App\Filament\Hr\Resources\OfferLetters;

use App\Filament\Hr\Resources\OfferLetters\Pages\CreateOfferLetter;
use App\Filament\Hr\Resources\OfferLetters\Pages\EditOfferLetter;
use App\Filament\Hr\Resources\OfferLetters\Pages\ListOfferLetters;
use App\Filament\Hr\Resources\OfferLetters\Pages\ViewOfferLetter;
use App\Filament\Hr\Resources\OfferLetters\Schemas\OfferLetterForm;
use App\Filament\Hr\Resources\OfferLetters\Schemas\OfferLetterInfolist;
use App\Filament\Hr\Resources\OfferLetters\Tables\OfferLettersTable;
use App\Models\OfferLetter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OfferLetterResource extends Resource
{
    protected static ?string $model = OfferLetter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'position';

    public static function form(Schema $schema): Schema
    {
        return OfferLetterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OfferLetterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfferLettersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOfferLetters::route('/'),
            'create' => CreateOfferLetter::route('/create'),
            'view' => ViewOfferLetter::route('/{record}'),
            'edit' => EditOfferLetter::route('/{record}/edit'),
        ];
    }
}
