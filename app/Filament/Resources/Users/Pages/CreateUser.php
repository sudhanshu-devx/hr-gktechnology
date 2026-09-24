<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // Full width layout
    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    /**
     * 🔐 ATOMIC CREATE — ALL OR NOTHING
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            // Extract nested relations
            $kycData  = $data['kyc']  ?? null;
            $bankData = $data['bank'] ?? null;

            // Remove them from main user data
            unset($data['kyc'], $data['bank']);

            // 1️⃣ Create USER
            $user = static::getModel()::create($data);

            // 2️⃣ Create KYC (if provided)
            if (! empty($kycData)) {
                $user->kyc()->create($kycData);
            }

            // 3️⃣ Create BANK (if provided)
            if (! empty($bankData)) {
                $user->bank()->create($bankData);
            }

            // If ANY exception occurs above → EVERYTHING ROLLS BACK
            return $user;
        });
    }

    // Redirect back to list after success
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
