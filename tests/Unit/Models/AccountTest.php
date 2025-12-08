<?php

declare(strict_types=1);

use App\Models\Account;

it('to array account', function (): void {
    $account = Account::factory()->create()->fresh();

    expect(array_keys($account->toArray()))
        ->toEqual([
            'id',
            'code',
            'name',
            'account_type_id',
            'parent_id',
            'is_summary',
            'description',
            'opening_balance',
            'opening_balance_type',
            'opening_balance_date',
            'currency',
            'is_active',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});
