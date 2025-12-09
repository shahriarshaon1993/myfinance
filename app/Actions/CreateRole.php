<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\RoleDto;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role as SpatieRole;

final class CreateRole
{
    public function handle(RoleDto $roleDto): SpatieRole
    {
        return DB::transaction(function () use ($roleDto): SpatieRole {
            $payload = $roleDto->toArray();

            $role = Role::create(['name' => $payload['name']]);

            $role->permissions()->sync($payload['permissions'] ?? []);

            return $role;
        });
    }
}
