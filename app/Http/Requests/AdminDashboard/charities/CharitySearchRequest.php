<?php

namespace App\Http\Requests\AdminDashboard\charities;

use App\Enums\AdminPermissions;
use App\Enums\CharityStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CharitySearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can(AdminPermissions::ShowCharities->value, 'api');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(CharityStatus::values())],
        ];
    }
}
