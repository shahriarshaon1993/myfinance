<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AccountType;

final class DeleteType
{
    public function handle(AccountType $type): ?bool
    {
        return $type->delete();
    }
}
