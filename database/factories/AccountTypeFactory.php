<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AccountType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountType>
 */
final class AccountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->name,
            'name' => $this->faker->name,
            'description' => $this->faker->text(),
            'normal_balance_debit' => $this->faker->boolean(),
            'is_writable' => true,
        ];
    }
}
