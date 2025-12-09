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
    /**
     * Handle the action.
     */
    public function handle(UserDto $userDto): User
    {
        return DB::transaction(function () use ($userDto): User {
            /** @var string $password */
            $password = $userDto->password;

            $user = User::create([
                'name' => $userDto->name,
                'email' => $userDto->email,
                'phone' => $userDto->phone,
                'is_active' => $userDto->isActive,
                'password' => Hash::make($password),
            ]);

            if ($userDto->avatar instanceof UploadedFile) {
                $user->addMedia($userDto->avatar, 'avatar');
            }

            $this->syncRoles($user, $userDto->roles);
            $this->syncPermissions($user, $userDto->permissions);

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
