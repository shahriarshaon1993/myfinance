<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Permission::factory()->create(['name' => 'view activity']);
    Permission::factory()->create(['name' => 'delete activity']);
});

it('allows user with permission to view activity', function (): void {
    $user = User::factory()->create();

    $user->givePermissionTo('view activity');

    expect($user->can('viewAny', Activity::class))->toBeTrue();
});

it('forbids unauthorized user to view activity', function (): void {
    $user = User::factory()->create();

    expect($user->can('viewAny', Activity::class))->toBeFalse();
});

it('allows user with permission to delete activity', function (): void {
    $user = User::factory()->create();
    $activity = Activity::factory()->create();

    $user->givePermissionTo('delete activity');

    expect($user->can('delete', $activity))->toBeTrue();
});

it('forbids unauthorized user to delete activity', function (): void {
    $user = User::factory()->create();
    $activity = Activity::factory()->create();

    expect($user->can('delete', $activity))->toBeFalse();
});

it('allows user with permission to delete any activity', function (): void {
    $user = User::factory()->create();
    $activity = Activity::factory()->create();

    $user->givePermissionTo('delete activity');

    expect($user->can('deleteAny', $activity))->toBeTrue();
});

it('forbids unauthorized user to delete any activity', function (): void {
    $user = User::factory()->create();
    $activity = Activity::factory()->create();

    expect($user->can('deleteAny', $activity))->toBeFalse();
});
