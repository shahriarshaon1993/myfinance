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
            $data = [
                'name' => $userDto->name,
                'email' => $userDto->email,
                'phone' => $userDto->phone,
                'is_active' => $userDto->isActive,
            ];

            if (! in_array($userDto->password, [null, '', '0'], true)) {
                $data['password'] = Hash::make($userDto->password);
            }

            $user->update($data);

            $this->updateAvatar($user, $userDto);

            $this->syncRoles($user, $userDto->roles);
            $this->syncPermissions($user, $userDto->permissions);

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
