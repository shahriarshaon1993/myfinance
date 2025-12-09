<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

/**
 * @phpstan-type UserData array{
 *     name: string,
 *     email: string,
 *     phone: string,
 *     is_active: string,
 *     password: string|null,
 *     avatar: UploadedFile|null,
 *     avatar_removed: bool,
 *     roles: array<int>,
 *     permissions: array<int>
 * }
 */
final readonly class UserDto
{
    /**
     * @param  array<int>  $roles
     * @param  array<int>  $permissions
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $isActive,

        public ?string $password = null,

        public ?UploadedFile $avatar = null,
        public bool $avatarRemoved = false,

        public array $roles = [],
        public array $permissions = [],
    ) {
        //
    }

    /**
     * @param  UserData  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            isActive: $data['is_active'],
            password: $data['password'],
            avatar: $data['avatar'],
            avatarRemoved: (bool) $data['avatar_removed'],
            roles: $data['roles'],
            permissions: $data['permissions'],
        );
    }

    /**
     * @return UserData
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->isActive,
            'password' => $this->password,
            'avatar' => $this->avatar,
            'avatar_removed' => $this->avatarRemoved,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
        ];
    }
}
