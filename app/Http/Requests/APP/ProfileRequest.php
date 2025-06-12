<?php

namespace App\Http\Requests\APP;

use App\Enums\CharityStatus;
use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole(UserRoles::Charity->value) &&
            auth()->user()->charity->status == CharityStatus::Approved->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        $charity = DB::table('charities')->where('charity_id', $userId)->first();
        $charityId = $charity ? $charity->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users', 'email')->ignore($userId),
                'max:100'
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('charities', 'phone_number')->ignore($charityId),
            ],
            'location' => 'required|string|max:255',
            'commercial_register' => 'nullable|image|mimes:jpeg,png,jpg,heic,webp|max:5120',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,heic,webp|max:5120'
        ];
    }
}
