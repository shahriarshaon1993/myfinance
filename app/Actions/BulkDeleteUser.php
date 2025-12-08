<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\BulkDestroyDto;
use App\Models\User;

final class BulkDeleteUser
{
    public function handle(BulkDestroyDto $bulkDestroyDto): int
    {
        return User::destroy($bulkDestroyDto->toArray());
    }
}
