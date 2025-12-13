<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\AccountTypeDto;
use App\Models\AccountType;

final class UpdateAccountType
{
    public function handle(AccountTypeDto $typeDto, AccountType $type): AccountType
    {
        $type->update($typeDto->toArray());

        return $type;
    }
}
