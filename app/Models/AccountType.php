<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Traits\HasActivityLog;
use Carbon\CarbonInterface;
use Database\Factories\AccountTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read int $id
 * @property-read string $code
 * @property-read string $name
 * @property-read string $description
 * @property-read bool $normal_balance_debit
 * @property-read bool $is_writable
 * @property-read ActiveStatus $is_active
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class AccountType extends Model
{
    /** @use HasFactory<AccountTypeFactory> */
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
        'description' => 'string',
        'normal_balance_debit' => 'boolean',
        'is_writable' => 'boolean',
        'is_active' => ActiveStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
