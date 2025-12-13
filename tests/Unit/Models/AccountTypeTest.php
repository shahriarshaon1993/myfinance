<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('to array account type model', function (): void {
    $accountType = AccountType::factory()->create()->fresh();

    expect(array_keys($accountType->toArray()))
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

it('can has many accounts type', function (): void {
    $account = AccountType::factory()->create()->fresh();

    $relation = $account->accounts();

    expect($relation)->toBeInstanceOf(HasMany::class);
    expect($relation->getRelated())->toBeInstanceOf(Account::class);
});
