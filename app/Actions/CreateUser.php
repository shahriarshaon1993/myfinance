<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\UserDto;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class CreateUser
{
    public function handle(UserDto $userDto): User
    {
        return DB::transaction(function () use ($userDto): User {
            $payload = $userDto->toArray();

            /** @var string $password */
            $password = $payload['password'];

            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
                'is_active' => $payload['is_active'],
                'password' => Hash::make($password),
            ]);

            if ($payload['avatar'] instanceof UploadedFile) {
                $user->addMedia($payload['avatar'], 'avatar');
            }

            $this->syncRoles($user, $payload['roles']);
            $this->syncPermissions($user, $payload['permissions']);

            return $user;
        });
    }

    /**
     * @param  array<int>  $roleIds
     */
    private function syncRoles(User $user, array $roleIds): void
    {
        $roles = Role::findMany($roleIds);
        $user->syncRoles($roles);
    }

    /**
     * @param  array<int>  $permissionIds
     */
    private function syncPermissions(User $user, array $permissionIds): void
    {
        $permissions = Permission::findMany($permissionIds);
        $user->syncPermissions($permissions);
    }
}
