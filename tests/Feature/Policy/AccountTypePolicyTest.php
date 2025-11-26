<?php

declare(strict_types=1);

use App\Models\AccountType;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create(['name' => 'view types']);
    Permission::factory()->create(['name' => 'create type']);
    Permission::factory()->create(['name' => 'update type']);
    Permission::factory()->create(['name' => 'delete type']);
});

it('allows user with permission to view account types', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    $user->givePermissionTo('view types');

    expect($user->can('viewAny', $type))->toBeTrue();
});

it('forbids unauthorized user to view account types', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    expect($user->can('view', $type))->toBeFalse();
});

it('allows user with permission to create account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    $user->givePermissionTo('create type');

    expect($user->can('create', $type))->toBeTrue();
});

it('forbids unauthorized user to create account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    expect($user->can('view', $type))->toBeFalse();
});

it('allows user with permission to update account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    $user->givePermissionTo('update type');

    expect($user->can('update', $type))->toBeTrue();
});

it('allows user with permission to update a writable account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create(['is_writable' => true]);

    $user->givePermissionTo('update type');

    expect($user->can('update', $type))->toBeTrue();
});

it('denies update when account type is not writable', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create(['is_writable' => false]);

    $user->givePermissionTo('update type');

    expect($user->can('update', $type))->toBeFalse();
});

it('forbids unauthorized user to update account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    expect($user->can('update', $type))->toBeFalse();
});

it('allows user with permission to destroy account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    $user->givePermissionTo('delete type');

    expect($user->can('delete', $type))->toBeTrue();
});

it('allows user with permission to destroy a writable account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create(['is_writable' => true]);

    $user->givePermissionTo('delete type');

    expect($user->can('delete', $type))->toBeTrue();
});

it('denies destroy when account type is not writable', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create(['is_writable' => false]);

    $user->givePermissionTo('delete type');

    expect($user->can('delete', $type))->toBeFalse();
});

it('forbids unauthorized user to destroy account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    expect($user->can('delete', $type))->toBeFalse();
});

it('allows user with permission to destroy any account type', function (): void {
    $user = User::factory()->create();
    $type = AccountType::factory()->create();

    $user->givePermissionTo('delete type');

    expect($user->can('deleteAny', $type))->toBeTrue();
});
