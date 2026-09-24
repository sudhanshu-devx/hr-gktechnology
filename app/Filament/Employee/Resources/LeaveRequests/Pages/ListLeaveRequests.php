<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Pages;

use App\Filament\Employee\Resources\LeaveRequests\LeaveRequestResource;
use App\Filament\Employee\Widgets\LeaveBalanceWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // ✅ THIS IS WHAT WAS MISSING
    protected function getHeaderWidgets(): array
    {
        return [
            LeaveBalanceWidget::class,
        ];
    }
}
