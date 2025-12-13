<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\UserDto;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UpdateUser
{
    public function handle(UserDto $userDto, User $user): User
    {
        return DB::transaction(function () use ($userDto, $user): User {
            $payload = $userDto->toArray();

            $data = [
                'name' => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
                'is_active' => $payload['is_active'],
            ];

            if (! in_array($payload['password'], [null, '', '0'], true)) {
                $data['password'] = Hash::make($payload['password']);
            }

            $user->update($data);

            $this->updateAvatar($user, $userDto);

            $this->syncRoles($user, $payload['roles']);
            $this->syncPermissions($user, $payload['permissions']);

            if ($user->is_active->value !== 'active') {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            return $user;
        });
    }

    private function updateAvatar(User $user, UserDto $userDto): void
    {
        if ($userDto->avatarRemoved) {
            $user->clearMedia('avatar');
        }

        if ($userDto->avatar instanceof \Illuminate\Http\UploadedFile) {
            $user->clearMedia('avatar');

            $user->addMedia($userDto->avatar, 'avatar');
        }
    }

    /**
     * @param  array<int>  $roleIds
     */
    private function syncRoles(User $user, array $roleIds = []): void
    {
        $roles = Role::findMany($roleIds);
        $user->syncRoles($roles);
    }

    /**
     * @param  array<int>  $permissionIds
     */
    private function syncPermissions(User $user, array $permissionIds = []): void
    {
        $permissions = Permission::findMany($permissionIds);
        $user->syncPermissions($permissions);
    }
}
