<?php

declare(strict_types=1);

namespace App\Http\Requests\Accounting;

use App\Enums\ActiveStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:2', 'max:50', 'unique:accounts,code'],
            'name' => ['required', 'string', 'min:2', 'max:191'],
            'account_type_id' => ['required', 'integer', 'exists:account_types,id'],
            'parent_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'is_summary' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
            'opening_balance' => ['nullable', 'numeric'],
            'opening_balance_type' => ['nullable', 'in:D,C'],
            'opening_balance_date' => ['nullable', 'date'],
            'is_active' => [Rule::enum(ActiveStatus::class)],
        ];
    }
}
