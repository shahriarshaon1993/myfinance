<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
final class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('ACC-###'),
            'name' => $this->faker->words(2, true),
            'account_type_id' => AccountType::factory(),
            'parent_id' => null,
            'is_active' => ActiveStatus::Active->value,
            'is_summary' => $this->faker->boolean(20),
            'description' => $this->faker->optional()->paragraph(),
            'opening_balance' => $this->faker->randomFloat(6, 0, 500000),
            'opening_balance_type' => $this->faker->randomElement(['D', 'C']),
            'opening_balance_date' => $this->faker->optional()->date(),
            'currency' => 'BDT',
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function childOf(Account $parent): self
    {
        return $this->state(fn (): array => [
            'parent_id' => $parent->id,
            'is_summary' => false,
        ]);
    }

    public function summary(): self
    {
        return $this->state(fn (): array => [
            'is_summary' => true,
        ]);
    }
}
