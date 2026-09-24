<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    // Full width layout
    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * 🔐 ATOMIC UPDATE — ALL OR NOTHING
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {

            // Extract nested relations
            $kycData  = $data['kyc']  ?? null;
            $bankData = $data['bank'] ?? null;

            // Remove them from main data
            unset($data['kyc'], $data['bank']);

            // 1️⃣ Update USER
            $record->update($data);

            // 2️⃣ Update or create KYC
            if (! empty($kycData)) {
                $record->kyc()->updateOrCreate(
                    ['user_id' => $record->id],
                    $kycData
                );
            }

            // 3️⃣ Update or create BANK
            if (! empty($bankData)) {
                $record->bank()->updateOrCreate(
                    ['user_id' => $record->id],
                    $bankData
                );
            }

            // If ANY exception happens → EVERYTHING ROLLS BACK
            return $record;
        });
    }

    // Redirect back to user list after success
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
