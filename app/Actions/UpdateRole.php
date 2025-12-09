<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\RoleDto;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

final class UpdateRole
{
    /**
     * Handle the action.
     */
    public function handle(RoleDto $roleDto, Role $role): Role
    {
        return DB::transaction(function () use ($roleDto, $role): Role {
            $payload = $roleDto->toArray();

            $role->update(['name' => $payload['name']]);

            $role->permissions()->sync($payload['permissions'] ?? []);

            return $role;
        });
    }
}
