<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * @phpstan-type AccountData array{
 *     code: string,
 *     name: string,
 *     account_type_id: int,
 *     is_summary: boolean,
 *     opening_balance: int|float,
 *     is_active: string,
 *     parent_id: int|null,
 *     description: string|null,
 *     opening_balance_date: string|null,
 *     opening_balance_type: string|null,
 * }
 */
final readonly class AccountDto
{
    public function __construct(
        public string $code,
        public string $name,
        public int $accountTypeId,
        public bool $isSummary,
        public int|float $openingBalance,
        public string $isActive,
        public ?int $parentId = null,
        public ?string $description = null,
        public ?string $openingBalanceDate = null,
        public ?string $openingBalanceType = null,
    ) {
        //
    }

    /**
     * @param  AccountData  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            accountTypeId: $data['account_type_id'],
            isSummary: (bool) $data['is_summary'],
            openingBalance: $data['opening_balance'],
            isActive: $data['is_active'],
            parentId: $data['parent_id'],
            description: $data['description'],
            openingBalanceDate: $data['opening_balance_date'],
            openingBalanceType: $data['opening_balance_type'],
        );
    }

    /**
     * @return AccountData
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'account_type_id' => $this->accountTypeId,
            'is_summary' => $this->isSummary,
            'opening_balance' => $this->openingBalance,
            'is_active' => $this->isActive,
            'parent_id' => $this->parentId,
            'description' => $this->description,
            'opening_balance_date' => $this->openingBalanceDate,
            'opening_balance_type' => $this->openingBalanceType,
        ];
    }
}
