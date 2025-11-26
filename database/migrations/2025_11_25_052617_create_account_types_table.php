<?php

declare(strict_types=1);

use App\Enums\ActiveStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique(); // e.g. ASSET, LIABILITY, INCOME, EXPENSE, EQUITY
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->boolean('normal_balance_debit')->default(true)
                ->comment('true => normal debit balance (Assets, Expenses)');
            $table->boolean('is_writable')->default(true);
            $table->enum('is_active', ActiveStatus::cases())
                ->default(ActiveStatus::Active->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
