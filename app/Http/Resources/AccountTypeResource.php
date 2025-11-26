<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AccountType;
use App\Supports\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AccountType
 */
final class AccountTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'is_writable' => $this->is_writable,
            'status' => [
                'value' => $this->is_active->value,
                'label' => $this->is_active->label(),
                'color' => $this->is_active->color(),
            ],
            'normal_balance_debit' => $this->normal_balance_debit,
            'created_at' => DateFormat::format($this->created_at),
            'updated_at' => DateFormat::format($this->updated_at),
        ];
    }
}
