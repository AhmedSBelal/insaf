<?php

namespace App\Http\Requests\APP;

use App\Enums\CharityStatus;
use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

class NotificationSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(UserRoles::Charity->value)
            && auth()->user()->charity->status == CharityStatus::Approved->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'general_notification' => 'required|boolean',
            'order_updates' => 'required|boolean',
            'promotions_offers' => 'required|boolean',
            'announcements' => 'required|boolean',
            'call_sound' => 'required|boolean',
            'vibration' => 'required|boolean',
            'notification_types' => 'required|array',
            'notification_types.*' => 'required|in:push,email,sms'
        ];
    }
}
