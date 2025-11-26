<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AccountType;
use App\Models\User;

final class AccountTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view types');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view types');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create type');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AccountType $type): bool
    {
        if (! $user->hasPermissionTo('update type')) {
            return false;
        }

        return $type->is_writable;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AccountType $type): bool
    {
        if (! $user->hasPermissionTo('delete type')) {
            return false;
        }

        return $type->is_writable;
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete type');
    }
}
