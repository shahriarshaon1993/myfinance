<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\AccountTypeDto;
use App\Models\AccountType;

final class CreateAccountType
{
    public function handle(AccountTypeDto $type): AccountType
    {
        return AccountType::create($type->toArray());
    }
}
