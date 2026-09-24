<?php

namespace App\Filament\Resources\LeaveRequests\Pages;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

    /**
     * 🔒 Remove ALL header actions (Delete, etc.)
     */
    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * 🔒 Remove Save button completely
     */
    protected function getFormActions(): array
    {
        return [];
    }

    /**
     * 🔒 Extra safety: block form submission
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        abort(403, 'Admins cannot edit leave requests.');
    }
}
