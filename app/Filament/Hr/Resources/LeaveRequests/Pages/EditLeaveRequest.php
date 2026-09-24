<?php

namespace App\Filament\Hr\Resources\LeaveRequests\Pages;

use App\Filament\Hr\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => $this->record->status === 'pending'),
        ];
    }

    /**
     * 🔒 HR can only edit PENDING leave requests
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->status !== 'pending') {
            abort(403, 'You cannot edit an approved or rejected leave request.');
        }

        return $data;
    }

    /**
     * 🔒 HR can never approve / reject
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Force status to remain pending
        $data['status'] = 'pending';

        // Strip any approval-related fields just in case
        unset(
            $data['approved_by'],
            $data['approved_at'],
            $data['rejection_reason']
        );

        return $data;
    }
}
