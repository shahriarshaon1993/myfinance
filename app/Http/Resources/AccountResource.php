<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Account
 */
final class AccountResource extends JsonResource
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
            'type' => $this->type ? [
                'id' => $this->type->id,
                'name' => $this->type->name,
            ] : null,
            'parent' => $this->parent ? [
                'id' => $this->parent->id,
                'name' => $this->parent->name,
            ] : null,
            'is_summary' => $this->is_summary,
            'description' => $this->description,
            'opening_balance' => $this->opening_balance,
            'opening_balance_type' => $this->opening_balance_type,
            'opening_balance_date' => $this->opening_balance_date?->format('Y-m-d'),
            'currency' => $this->currency,
            'is_active' => $this->is_active,
            'status' => [
                'value' => $this->is_active->value,
                'label' => $this->is_active->label(),
                'color' => $this->is_active->color(),
            ],
            'children' => self::collection($this->whenLoaded('children')),
        ];
    }
}
