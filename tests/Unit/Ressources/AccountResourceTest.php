<?php

declare(strict_types=1);

use App\Http\Resources\AccountResource;
use App\Models\Account;

it('account resource transforms correctly', function (): void {
    $account = Account::factory()->create(['name' => 'Test Account']);

    $result = new AccountResource($account)->toArray(request());

    expect($result)
        ->toBeArray()
        ->and($result['id'])->toBe($account->id)
        ->and($result['name'])->toBe('Test Account');
});
