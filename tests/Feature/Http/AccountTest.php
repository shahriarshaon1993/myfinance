<?php

declare(strict_types=1);

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the accounts with paginate currently', function () {
    $accounts = Account::factory()->count(3)->create();

    $response = $this->get(route('accounting.accounts.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('accounting/account/Index')
        ->has('accounts')
        ->has('filters')
    );
});

it('can displayed the account create page', function (): void {
    $response = $this->get(route('accounting.accounts.create'));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('accounting/account/Create')
    );
});

it('can delete an account', function () {
    $account = Account::factory()->create();

    $response = $this->delete(route('accounting.types.destroy', $account->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('accounts', [
        'id' => $account->id,
    ]);
});
