<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * @phpstan-type FilterData array{
 *     sort_field: string,
 *     sort_order: string,
 *     per_page: int,
 *     search: string|null
 * }
 */
final readonly class FilterDto
{
    public function __construct(
        public string $sort_field,
        public string $sort_order,
        public int $per_page,
        public ?string $search = null,
    ) {
        //
    }

    /**
     * @param  FilterData  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sort_field: $data['sort_field'],
            sort_order: $data['sort_order'],
            per_page: $data['per_page'],
            search: $data['search'],
        );
    }

    /**
     * @return FilterData
     */
    public function toArray(): array
    {
        return [
            'search' => $this->search,
            'sort_field' => $this->sort_field,
            'sort_order' => $this->sort_order,
            'per_page' => $this->per_page,
        ];
    }
}
