<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AccountType;

final class BulkDeleteType
{
    /**
     * @param  int[]  $typeIds
     */
    public function handle(array $typeIds): int
    {
        $ids = AccountType::query()
            ->whereIn('id', $typeIds)
            ->where('is_writable', true)
            ->pluck('id')
            ->toArray();

        return AccountType::destroy($ids);
    }
}
