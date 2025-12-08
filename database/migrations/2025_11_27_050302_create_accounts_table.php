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
        Schema::create('accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');

            $table->foreignId('account_type_id')->constrained('account_types')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->index()->constrained('accounts')->onDelete('set null');
            $table->boolean('is_summary')->default(false)->index()->comment('true = group, false = Ledger Account');

            $table->longText('description')->nullable();

            $table->decimal('opening_balance', 20, 6)->default(0);
            $table->enum('opening_balance_type', ['D', 'C'])->default('D')->comment('D = Debit, C = Credit');
            $table->date('opening_balance_date')->nullable();

            $table->string('currency', 3)->default('BDT');
            $table->enum('is_active', ActiveStatus::cases())
                ->default(ActiveStatus::Active->value)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes('deleted_at', 0)->index();

            $table->index(['parent_id', 'is_active']);
            $table->index(['account_type_id', 'is_summary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
