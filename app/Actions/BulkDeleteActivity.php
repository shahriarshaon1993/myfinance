<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\BulkDestroyDto;
use Spatie\Activitylog\Models\Activity;

final class BulkDeleteActivity
{
    public function handle(BulkDestroyDto $bulkDestroyDto): int
    {
        return Activity::destroy($bulkDestroyDto->toArray());
    }
}
