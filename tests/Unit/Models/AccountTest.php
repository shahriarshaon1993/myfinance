<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('to array account model', function (): void {
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

it('can belongs to account type', function (): void {
    $account = Account::factory()->create();

    $relation = $account->type();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
    expect($relation->getRelated())->toBeInstanceOf(AccountType::class);
});

it('can belongs to parent account', function (): void {
    $account = Account::factory()->create();

    $relation = $account->parent();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
    expect($relation->getRelated())->toBeInstanceOf(Account::class);
});

it('can has many children account', function (): void {
    $account = Account::factory()->create();

    $relation = $account->children();

    expect($relation)->toBeInstanceOf(HasMany::class);
    expect($relation->getRelated())->toBeInstanceOf(Account::class);
});

it('can belongs to user as creator', function (): void {
    $account = Account::factory()->create();

    $relation = $account->creator();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
    expect($relation->getRelated())->toBeInstanceOf(User::class);
});

it('can belongs to user as updater', function (): void {
    $account = Account::factory()->create();

    $relation = $account->updater();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
    expect($relation->getRelated())->toBeInstanceOf(User::class);
});
