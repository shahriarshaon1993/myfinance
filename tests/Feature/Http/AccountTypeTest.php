<?php

declare(strict_types=1);

use App\Enums\ActiveStatus;
use App\Models\AccountType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    actingAsAdmin();
});

it('can displayed the account type with paginate currently', function (): void {
    AccountType::factory()->create();

    $response = $this->get(route('accounting.types.index'));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('accounting/type/Index')
            ->has('types', 3)
            ->has('types.meta', fn ($meta) => $meta
                ->where('per_page', 15)
                ->where('current_page', 1)
                ->where('last_page', 1)
                ->etc()
            )
    );
});

it('can displayed the account type with search filter currently', function (): void {
    AccountType::factory()->create(['name' => 'Test Account']);

    $response = $this->get(route('accounting.types.index', ['search' => 'Test']));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): AssertableJson => $page
            ->component('accounting/type/Index')
            ->has('types.data', 1)
            ->where('types.data.0.name', 'Test Account')
            ->etc()
    );
});

it('create a new account type with valid data', function (): void {
    $data = [
        'code' => 'EQUITY',
        'name' => 'Equity',
        'description' => 'For testing purposes',
        'normal_balance_debit' => false,
        'is_writable' => false,
        'is_active' => ActiveStatus::Active->value,
    ];

    $response = $this->post(route('accounting.types.store'), $data);

    $response->assertStatus(302);
    $response->assertRedirect(route('accounting.types.index'));
});

it('updates an existing account type with full data', function (): void {
    $type = AccountType::factory()->create();

    $data = [
        'code' => 'EQUITY',
        'name' => 'Equity',
        'description' => 'For testing purposes',
        'normal_balance_debit' => false,
        'is_writable' => false,
        'is_active' => ActiveStatus::Active->value,
    ];

    $response = $this->put(route('accounting.types.update', $type), $data);

    $response->assertStatus(302);
    $response->assertRedirect(route('accounting.types.index'));
    $this->assertDatabaseHas('account_types', [
        'id' => $type->id,
        'name' => 'Equity',
    ]);
});

it('can delete account type', function (): void {
    $type = AccountType::factory()->create();

    $response = $this->delete(route('accounting.types.destroy', $type->id));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('account_types', [
        'id' => $type->id,
    ]);
});

it('can bulk delete multiple account type', function (): void {
    $types = AccountType::factory()->count(2)->create();

    $payload = ['ids' => $types->pluck('id')->toArray()];

    $response = $this->delete(route('accounting.types.bulk-destroy'), $payload);

    $response->assertRedirect();
    foreach ($types as $type) {
        expect(AccountType::query()->find($type->id))->toBeNull();
    }
});
