<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

final class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['code' => 'ASSET', 'name' => 'Asset', 'normal_balance_debit' => true, 'is_writable' => false],
            ['code' => 'LIABILITY', 'name' => 'Liability', 'normal_balance_debit' => false, 'is_writable' => false],
            ['code' => 'INCOME', 'name' => 'Income', 'normal_balance_debit' => false, 'is_writable' => false],
            ['code' => 'EXPENSE', 'name' => 'Expense', 'normal_balance_debit' => true, 'is_writable' => false],
            ['code' => 'EQUITY', 'name' => 'Equity', 'normal_balance_debit' => false, 'is_writable' => false],
        ];

        foreach ($types as $type) {
            AccountType::updateOrCreate($type);
        }
    }
}
