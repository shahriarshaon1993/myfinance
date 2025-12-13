<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateAccount;
use App\Actions\DeleteAccount;
use App\Actions\GetAccounts;
use App\DTOs\AccountDto;
use App\DTOs\FilterDto;
use App\Http\Requests\Accounting\StoreAccountRequest;
use App\Http\Requests\FilterRequest;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class AccountController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request, GetAccounts $action): Response
    {
        Gate::authorize('viewAny', Account::class);

        $filters = FilterDto::fromArray($request->validatedFilters());

        $data = $action->handle($filters);

        return Inertia::render('accounting/account/Index', [
            'accounts' => $data['tree'],
            'filters' => $data['filters'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Account::class);

        return Inertia::render('accounting/account/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request, CreateAccount $action): RedirectResponse
    {
        Gate::authorize('create', Account::class);

        /** @var array{code: string, name: string, account_type_id: int, is_summary: bool, opening_balance: int|float, is_active: string, parent_id: int|null, description: string|null, opening_balance_date: string|null, opening_balance_type: string|null} $data */
        $data = $request->validated();

        $action->handle(AccountDto::fromArray($data));

        return to_route('accounting.accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account, DeleteAccount $action): RedirectResponse
    {
        Gate::authorize('delete', $account);

        $action->handle($account);

        return to_route('accounting.accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
