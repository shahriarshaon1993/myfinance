<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class BulkDestroyDto
{
    /**
     * @param  int[]  $ids
     */
    public function __construct(
        public array $ids,
    ) {
        //
    }

    /**
     * @param  array{ids: int[]}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ids: $data['ids'],
        );
    }

    /**
     * @return array<int>
     */
    public function toArray(): array
    {
        return $this->ids;
    }
}
