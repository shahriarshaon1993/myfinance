<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Account;

final class DeleteAccount
{
    public function handle(Account $account): ?bool
    {
        return $account->delete();
    }
}
