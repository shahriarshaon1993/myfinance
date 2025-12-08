<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\BulkDestroyDto;
use App\Models\AccountType;

final class BulkDeleteType
{
    public function handle(BulkDestroyDto $bulkDestroyDto): int
    {
        $ids = AccountType::query()
            ->whereIn('id', $bulkDestroyDto->toArray())
            ->where('is_writable', true)
            ->pluck('id')
            ->toArray();

        return AccountType::destroy($ids);
    }
}
