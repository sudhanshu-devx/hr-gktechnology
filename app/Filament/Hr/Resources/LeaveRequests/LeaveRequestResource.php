<?php

namespace App\Filament\Hr\Resources\LeaveRequests;

use App\Filament\Hr\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Hr\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Hr\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Hr\Resources\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Hr\Resources\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($q) use ($user) {
                // Employee leaves
                $q->whereHas('user.roles', function ($r) {
                    $r->where('name', 'employee');
                });

                // OR HR's own leaves
                $q->orWhere('user_id', $user->id);
            });
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
