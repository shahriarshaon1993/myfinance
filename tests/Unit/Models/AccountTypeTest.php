<?php

declare(strict_types=1);

use App\Models\AccountType;

it('to array account type', function (): void {
    $type = AccountType::factory()->create()->fresh();

    expect(array_keys($type->toArray()))
        ->toEqual([
            'id',
            'code',
            'name',
            'description',
            'normal_balance_debit',
            'is_writable',
            'is_active',
            'created_at',
            'updated_at',
        ]);
});
