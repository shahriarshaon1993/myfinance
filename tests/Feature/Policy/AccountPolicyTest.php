<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create(['name' => 'view accounts']);
    Permission::factory()->create(['name' => 'create account']);
    Permission::factory()->create(['name' => 'update account']);
    Permission::factory()->create(['name' => 'delete account']);
});

it('allows user with permission to view any accounts', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('view accounts');

    expect($user->can('viewAny', Account::class))->toBeTrue();
});

it('forbids unauthorized user to view any accounts', function (): void {
    $user = User::factory()->create();

    expect($user->can('viewAny', Account::class))->toBeFalse();
});

it('allows user with permission to view accounts', function (): void {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    $user->givePermissionTo('view accounts');

    expect($user->can('view', $account))->toBeTrue();
});

it('forbids unauthorized user to view accounts', function (): void {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    expect($user->can('view', $account))->toBeFalse();
});

it('allows user with permission to create account', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('create account');

    expect($user->can('create', Account::class))->toBeTrue();
});

it('forbids unauthorized user to create account', function (): void {
    $user = User::factory()->create();

    expect($user->can('create', Account::class))->toBeFalse();
});

it('allows user with permission to update account', function (): void {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    $user->givePermissionTo('update account');

    expect($user->can('update', $account))->toBeTrue();
});

it('forbids unauthorized user to update account', function (): void {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    expect($user->can('update', $account))->toBeFalse();
});

it('allows user with permission to destroy account', function (): void {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    $user->givePermissionTo('delete account');

    expect($user->can('delete', $account))->toBeTrue();
});

it('forbids unauthorized user to destroy account', function (): void {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    expect($user->can('delete', $account))->toBeFalse();
});
