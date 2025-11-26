<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\TypeDto;
use App\Models\AccountType;

final class CreateType
{
    public function handle(TypeDto $type): AccountType
    {
        return AccountType::create([
            'code' => $type->code,
            'name' => $type->name,
            'description' => $type->description,
            'is_active' => $type->is_active,
            'normal_balance_debit' => $type->normal_balance_debit,
        ]);
    }
}
