<?php

declare(strict_types=1);

namespace App\Http\Requests\Accounting;

use App\Enums\ActiveStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreAccountTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Z_]+$/', 'unique:account_types,code'],
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'description' => ['nullable', 'string', 'min:2', 'max:500'],
            'normal_balance_debit' => ['required', 'boolean'],
            'is_active' => [Rule::enum(ActiveStatus::class)],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'code.regex' => 'The code may only contain uppercase letters and underscores.',
        ];
    }
}
