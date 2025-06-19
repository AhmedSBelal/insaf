<?php

namespace App\Http\Requests\APP\Cart;

use App\Models\Surplus;
use Illuminate\Foundation\Http\FormRequest;

class AddItemRequest extends FormRequest
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
            'surplus_id' => 'required|exists:surpluses,id',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $surplus = Surplus::find($this->surplus_id);

                    if (!$surplus) {
                        $fail('Invalid surplus item.');
                        return;
                    }

                    if ($value > $surplus->quantity) {
                        $fail("The requested quantity exceeds available stock (Max: {$surplus->quantity}).");
                    }
                },
            ],
            'session_id' => 'nullable|string'
        ];
    }
}
