<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\AccountDto;
use App\Models\Account;

final class CreateAccount
{
    public function handle(AccountDto $accountDto): Account
    {
        return Account::create($accountDto->toArray());
    }
}
