<?php

namespace App\Http\Requests\AdminDashboard\suppliers;

use App\Enums\AdminPermissions;
use App\Enums\SupplierPermissions;
use App\Enums\SupplierStatus;
use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can(AdminPermissions::UpdateSupplier->value, 'api');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(SupplierStatus::values())],
        ];
    }
}
