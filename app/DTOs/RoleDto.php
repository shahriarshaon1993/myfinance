<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * @phpstan-type RoleData array{
 *     name: string,
 *     permissions: array<int>|null
 * }
 */
final readonly class RoleDto
{
    /**
     * @param  array<int>|null  $permissions
     */
    public function __construct(
        public string $name,
        public ?array $permissions = null,
    ) {
        //
    }

    /**
     * @param  RoleData  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            permissions: $data['permissions'],
        );
    }

    /**
     * @return RoleData
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}
