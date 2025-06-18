<?php

namespace App\Http\Requests\APP\Cart;

use App\Models\CartItem;
use App\Models\Surplus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    // Get the cart item ID from route parameters
                    $cartItemId = $this->route('item');

                    // Find the cart item with its surplus relationship
                    $cartItem = CartItem::with('surplus')
                        ->find($cartItemId);

                    if (!$cartItem || !$cartItem->surplus) {
                        $fail('Invalid cart item or surplus item.');
                        return;
                    }

                    if ($value > $cartItem->surplus->quantity) {
                        $fail("The requested quantity exceeds available stock (Max: {$cartItem->surplus->quantity}).");
                    }
                },
            ],
        ];
    }
}
