<?php

namespace App\Filament\Resources\LeaveQuotaResource\Pages;

use App\Filament\Resources\LeaveQuotaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveQuota extends CreateRecord
{
    protected static string $resource = LeaveQuotaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['remaining_quota'] = $data['annual_quota'] - ($data['used_quota'] ?? 0);

        return $data;
    }
}
