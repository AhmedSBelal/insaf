<?php

namespace App\Http\Requests\API\Supplier;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Order;

class UpdateOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'exists:orders,id',
                function ($attribute, $value, $fail) {
                    $supplier = $this->user()->supplier;

                    $exists = Order::where('id', $value)
                        ->whereHas('surpluses', function ($query) use ($supplier) {
                            $query->where('supplier_id', $supplier->id);
                        })
                        ->exists();

                    if (! $exists) {
                        $fail('The selected order does not belong to your account.');
                    }
                },
            ],
            'status' => 'required|in:'.OrderStatus::getvalues(),
        ];
    }
}
