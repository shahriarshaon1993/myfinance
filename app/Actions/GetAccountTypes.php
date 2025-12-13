<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\FilterDto;
use App\Http\Resources\AccountTypeResource;
use App\Models\AccountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetAccountTypes
{
    /**
     * handle getting roles with filters.
     *
     * @return array{
     *     filters: array{search: string|null, sort_field: string, sort_order: string, per_page: int},
     *     types: AnonymousResourceCollection
     * }
     */
    public function handle(FilterDto $filterDto): array
    {
        $filters = $filterDto->toArray();

        $types = AccountType::query()
            ->when(isset($filters['search']), function (Builder $q) use ($filters): void {
                $q->where(function (Builder $query) use ($filters): void {
                    $query->where('code', 'LIKE', "%{$filters['search']}%")
                        ->orWhere('name', 'LIKE', "%{$filters['search']}%");
                });
            })
            ->orderBy($filters['sort_field'], $filters['sort_order'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return [
            'filters' => $filters,
            'types' => AccountTypeResource::collection($types),
        ];
    }
}
