<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BulkDeleteAccountType;
use App\Actions\CreateAccountType;
use App\Actions\DeleteAccountType;
use App\Actions\GetAccountTypes;
use App\Actions\UpdateAccountType;
use App\DTOs\AccountTypeDto;
use App\DTOs\BulkDestroyDto;
use App\DTOs\FilterDto;
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
    public function index(FilterRequest $request, GetAccountTypes $action): Response
    {
        Gate::authorize('viewAny', AccountType::class);

        $filters = FilterDto::fromArray($request->validatedFilters());

        $data = $action->handle($filters);

        return Inertia::render('accounting/type/Index', [
            'types' => $data['types'],
            'filters' => $data['filters'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountTypeRequest $request, CreateAccountType $action): RedirectResponse
    {
        Gate::authorize('create', AccountType::class);

        /** @var array{code: string, name: string, normal_balance_debit: bool, is_active: string, description: string|null} $data */
        $data = $request->validated();

        $action->handle(AccountTypeDto::fromArray($data));

        return to_route('accounting.types.index')
            ->with('success', 'Account type created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountTypeRequest $request, AccountType $type, UpdateAccountType $action): RedirectResponse
    {
        Gate::authorize('update', $type);

        /** @var array{code: string, name: string, normal_balance_debit: bool, is_active: string, description: string|null} $data */
        $data = $request->validated();

        $action->handle(AccountTypeDto::fromArray($data), $type);

        return to_route('accounting.types.index')
            ->with('success', 'Account type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $type, DeleteAccountType $action): RedirectResponse
    {
        Gate::authorize('delete', $type);

        $action->handle($type);

        return to_route('accounting.types.index')
            ->with('success', 'Account type deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(DeleteRequest $request, BulkDeleteAccountType $action): RedirectResponse
    {
        Gate::authorize('deleteAny', AccountType::class);

        /** @var array{ids: int[]} $data */
        $data = $request->validated();

        $action->handle(BulkDestroyDto::fromArray($data));

        return to_route('accounting.types.index')
            ->with('success', 'Selected account types deleted successfully.');
    }
}
