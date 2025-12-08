<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTypeController;

Route::middleware('auth')->prefix('accounting')->name('accounting.')->group(function (): void {
    Route::delete('types/bulk-destroy', [AccountTypeController::class, 'bulkDestroy'])
        ->name('types.bulk-destroy');
    Route::resource('types', AccountTypeController::class)
        ->except(['create', 'edit', 'show']);

    Route::resource('accounts', AccountController::class);
});
