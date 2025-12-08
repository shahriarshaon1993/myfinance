<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Traits\HasActivityLog;
use Carbon\CarbonInterface;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Account
 *
 * @property-read int $id
 * @property-read string $code
 * @property-read string $name
 * @property-read int $account_type_id
 * @property-read int|null $parent_id
 * @property-read bool $is_summary
 * @property-read string|null $description
 * @property-read string $opening_balance
 * @property-read string $opening_balance_type
 * @property-read CarbonInterface|null $opening_balance_date
 * @property-read string $currency
 * @property-read ActiveStatus $is_active
 * @property-read int|null $created_by
 * @property-read int|null $updated_by
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface $deleted_at
 * @property-read AccountType $type
 * @property-read Account|null $parent
 * @property-read Collection|Account[] $children
 * @property-read User|null $creator
 * @property-read User|null $updater
 */
final class Account extends Model
{
    /** @use HasFactory<AccountFactory> */
    use HasActivityLog, HasFactory, LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'account_type_id' => 'integer',
        'parent_id' => 'integer',
        'is_summary' => 'boolean',
        'description' => 'string',
        'opening_balance' => 'decimal:6',
        'opening_balance_type' => 'string',
        'opening_balance_date' => 'date',
        'currency' => 'string',
        'is_active' => ActiveStatus::class,
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<AccountType, $this>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * @return BelongsTo<Account, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Account, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
