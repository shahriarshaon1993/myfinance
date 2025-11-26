<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\FilterDto;
use App\Http\Resources\AccountTypeResource;
use App\Models\AccountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetTypes
{
    /**
     * handle getting roles with filters.
     *
     * @return array{filters: FilterDto, types: AnonymousResourceCollection}
     */
    public function handle(FilterDto $filters): array
    {
        $types = AccountType::query()
            ->when(isset($filters->search), function (Builder $q) use ($filters): void {
                $q->where(function (Builder $query) use ($filters): void {
                    $query->where('code', 'LIKE', "%{$filters->search}%")
                        ->orWhere('name', 'LIKE', "%{$filters->search}%");
                });
            })
            ->orderBy($filters->sort_field ?? 'id', $filters->sort_order ?? 'asc')
            ->paginate($filters->per_page)
            ->withQueryString();

        return [
            'filters' => $filters,
            'types' => AccountTypeResource::collection($types),
        ];
    }
}
