<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

final class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeAsset = AccountType::query()->where('code', 'ASSET')->first()->id;
        $typeLiability = AccountType::query()->where('code', 'LIABILITY')->first()->id;
        $typeEquity = AccountType::query()->where('code', 'EQUITY')->first()->id;
        $typeIncome = AccountType::query()->where('code', 'INCOME')->first()->id;
        $typeExpense = AccountType::query()->where('code', 'EXPENSE')->first()->id;

        // --- ASSETS ---
        $asset = Account::factory()->create([
            'code' => '1000',
            'name' => 'Assets',
            'account_type_id' => $typeAsset,
            'is_summary' => true,
        ]);

        $cash = Account::factory()->create([
            'code' => '1100',
            'name' => 'Cash & Cash Equivalents',
            'account_type_id' => $typeAsset,
            'parent_id' => $asset->id,
            'is_summary' => true,
        ]);

        Account::factory()->create([
            'code' => '1110',
            'name' => 'Petty Cash',
            'account_type_id' => $typeAsset,
            'parent_id' => $cash->id,
            'is_summary' => false,
        ]);

        Account::factory()->create([
            'code' => '1120',
            'name' => 'Cash in Bank',
            'account_type_id' => $typeAsset,
            'parent_id' => $cash->id,
            'is_summary' => false,
        ]);

        Account::factory()->create([
            'code' => '1200',
            'name' => 'Accounts Receivable',
            'account_type_id' => $typeAsset,
            'parent_id' => $asset->id,
            'is_summary' => false,
        ]);

        // --- LIABILITIES ---
        $liability = Account::factory()->create([
            'code' => '2000',
            'name' => 'Liabilities',
            'account_type_id' => $typeLiability,
            'is_summary' => true,
        ]);

        Account::factory()->create([
            'code' => '2100',
            'name' => 'Accounts Payable',
            'account_type_id' => $typeLiability,
            'parent_id' => $liability->id,
            'is_summary' => false,
        ]);

        // --- EQUITY ---
        $equity = Account::factory()->create([
            'code' => '3000',
            'name' => 'Owner\'s Equity',
            'account_type_id' => $typeEquity,
            'is_summary' => true,
        ]);

        Account::factory()->create([
            'code' => '3100',
            'name' => 'Owner\'s Capital',
            'account_type_id' => $typeEquity,
            'parent_id' => $equity->id,
            'is_summary' => false,
        ]);

        // --- INCOME ---
        $income = Account::factory()->create([
            'code' => '4000',
            'name' => 'Income',
            'account_type_id' => $typeIncome,
            'is_summary' => true,
        ]);

        Account::factory()->create([
            'code' => '4100',
            'name' => 'Sales Revenue',
            'account_type_id' => $typeIncome,
            'parent_id' => $income->id,
            'is_summary' => false,
        ]);

        // --- EXPENSE ---
        $expense = Account::factory()->create([
            'code' => '5000',
            'name' => 'Expenses',
            'account_type_id' => $typeExpense,
            'is_summary' => true,
        ]);

        Account::factory()->create([
            'code' => '5100',
            'name' => 'Office Supplies Expense',
            'account_type_id' => $typeExpense,
            'parent_id' => $expense->id,
            'is_summary' => false,
        ]);

        Account::factory()->create([
            'code' => '5200',
            'name' => 'Utility Expense',
            'account_type_id' => $typeExpense,
            'parent_id' => $expense->id,
            'is_summary' => false,
        ]);
    }
}
