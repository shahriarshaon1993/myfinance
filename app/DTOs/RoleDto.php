<?php

declare(strict_types=1);

namespace App\DTOs;

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
     * @param  array{name: string, permissions: array<int>|null}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            permissions: $data['permissions'],
        );
    }

    /**
     * @return array{name: string, permissions: array<int>|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}
