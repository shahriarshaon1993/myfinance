<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\FilterDto;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetRoles
{
    /**
     * handle getting roles with filters.
     *
     * @return array{
     *     filters: array{search: string|null, sort_field: string, sort_order: string, per_page: int},
     *     roles: AnonymousResourceCollection
     * }
     */
    public function handle(FilterDto $filterDto): array
    {
        $filters = $filterDto->toArray();

        $roles = Role::query()
            ->when(isset($filters['search']), function (Builder $q) use ($filters): void {
                $q->where('name', 'LIKE', '%'.$filters['search'].'%');
            })
            ->orderBy($filters['sort_field'], $filters['sort_order'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return [
            'filters' => $filters,
            'roles' => RoleResource::collection($roles),
        ];
    }
}
