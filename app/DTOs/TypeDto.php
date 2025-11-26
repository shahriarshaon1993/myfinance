<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\Request;

final class TypeDto
{
    public function __construct(
        public string $code,
        public string $name,
        public bool $normal_balance_debit,
        public string $is_active,
        public ?string $description = null,
    ) {
        //
    }

    public static function from(Request $request): self
    {
        return new self(
            code: $request->string('code')->value(),
            name: $request->string('name')->value(),
            normal_balance_debit: $request->boolean('normal_balance_debit'),
            is_active: $request->string('is_active')->value(),
            description: $request->string('description')->value(),
        );
    }
}
