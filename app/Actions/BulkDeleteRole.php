<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\BulkDestroyDto;
use App\Models\Role;

final class BulkDeleteRole
{
    public function handle(BulkDestroyDto $bulkDestroyDto): int
    {
        return Role::destroy($bulkDestroyDto->toArray());
    }
}
