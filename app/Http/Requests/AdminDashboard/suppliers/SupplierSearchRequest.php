<?php

namespace App\Http\Requests\AdminDashboard\suppliers;

use App\Enums\AdminPermissions;
use App\Enums\SupplierStatus;
<<<<<<< HEAD
use App\Enums\SuppliersType;
=======
>>>>>>> master
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can(AdminPermissions::ShowSuppliers->value, 'api');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(SupplierStatus::values())],
<<<<<<< HEAD
            'type' =>['nullable' , Rule::in(SuppliersType::values())],
=======
>>>>>>> master
        ];
    }
}
