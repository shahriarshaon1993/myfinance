<?php

declare(strict_types=1);

use App\Enums\ActiveStatus;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the accounts with paginate currently', function (): void {
    $accounts = Account::factory()->count(3)->create();

    $response = $this->get(route('accounting.accounts.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('accounting/account/Index')
        ->has('accounts')
        ->has('filters')
    );
});

it('nests child accounts under their parent in the accounts tree', function (): void {
    $parent = Account::factory()->create([
        'parent_id' => null,
        'is_summary' => true,
    ]);

    $child = Account::factory()->create([
        'parent_id' => $parent->id,
        'is_summary' => false,
    ]);

    $response = $this->get(route('accounting.accounts.index'));

    $response->assertStatus(200);
});

it('can displayed the account create page', function (): void {
    $response = $this->get(route('accounting.accounts.create'));

    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('accounting/account/Create')
    );
});

it('can create a new account with valid data', function (): void {
    $accountType = AccountType::factory()->create();

    $data = [
        'code' => '1000',
        'name' => 'Assets',
        'parent_id' => null,
        'opening_balance' => 0,
        'account_type_id' => $accountType->id,
        'is_summary' => true,
        'description' => 'lorem ipsum',
        'is_active' => ActiveStatus::Active->value,
        'opening_balance_date' => null,
        'opening_balance_type' => null,
    ];

    $response = $this->post(route('accounting.accounts.store'), $data);

    $response->assertStatus(302);
    $response->assertRedirect(route('accounting.accounts.store'));
});

it('can delete an account', function (): void {
    $account = Account::factory()->create();

    $response = $this->delete(route('accounting.accounts.destroy', $account->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('accounts', [
        'id' => $account->id,
    ]);
});
