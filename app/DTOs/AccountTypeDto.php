<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class AccountTypeDto
{
    public function __construct(
        public string $code,
        public string $name,
        public bool $normalBalanceDebit,
        public string $isActive,
        public ?string $description = null,
    ) {
        //
    }

    /**
     * @param  array{code: string, name: string, normal_balance_debit: bool, is_active: string, description: string|null}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            normalBalanceDebit: (bool) $data['normal_balance_debit'],
            isActive: $data['is_active'],
            description: $data['description'] ?? null,
        );
    }

    /**
     * @return array{code: string, name: string, normal_balance_debit: bool, is_active: string, description: string|null}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'normal_balance_debit' => $this->normalBalanceDebit,
            'is_active' => $this->isActive,
            'description' => $this->description,
        ];
    }
}
