<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\TypeDto;
use App\Models\AccountType;

final class UpdateType
{
    public function handle(TypeDto $typeDto, AccountType $type): AccountType
    {
        $type->update([
            'code' => $typeDto->code,
            'name' => $typeDto->name,
            'description' => $typeDto->description,
            'is_active' => $typeDto->is_active,
            'normal_balance_debit' => $typeDto->normal_balance_debit,
        ]);

        return $type;
    }
}
