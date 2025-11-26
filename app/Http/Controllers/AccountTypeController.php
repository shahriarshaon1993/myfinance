<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BulkDeleteType;
use App\Actions\CreateType;
use App\Actions\DeleteType;
use App\Actions\GetTypes;
use App\Actions\UpdateType;
use App\DTOs\BulkDestroyDto;
use App\DTOs\FilterDto;
use App\DTOs\TypeDto;
use App\Http\Requests\Accounting\StoreAccountTypeRequest;
use App\Http\Requests\Accounting\UpdateAccountTypeRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\FilterRequest;
use App\Models\AccountType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class AccountTypeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request, GetTypes $action): Response
    {
        Gate::authorize('viewAny', AccountType::class);

        $filters = FilterDto::from($request->validatedFilters());

        $data = $action->handle($filters);

        return Inertia::render('accounting/type/Index', [
            'types' => $data['types'],
            'filters' => $data['filters'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountTypeRequest $request, CreateType $action): RedirectResponse
    {
        Gate::authorize('create', AccountType::class);

        $action->handle(TypeDto::from($request));

        return to_route('accounting.types.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountTypeRequest $request, AccountType $type, UpdateType $action): RedirectResponse
    {
        Gate::authorize('update', $type);

        $action->handle(TypeDto::from($request), $type);

        return to_route('accounting.types.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $type, DeleteType $action): RedirectResponse
    {
        Gate::authorize('delete', $type);

        $action->handle($type);

        return to_route('accounting.types.index')
            ->with('success', 'Account type deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(DeleteRequest $request, BulkDeleteType $action): RedirectResponse
    {
        Gate::authorize('deleteAny', AccountType::class);

        /** @var array{ids: int[]} $data */
        $data = $request->validated();

        $action->handle(BulkDestroyDto::from($data)->ids);

        return to_route('accounting.types.index')
            ->with('success', 'Selected types deleted successfully.');
    }
}
