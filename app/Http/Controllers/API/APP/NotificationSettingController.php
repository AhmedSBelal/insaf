<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Http\Requests\APP\NotificationSettingRequest;
use App\Http\Resources\APP\NotificationSettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    use ApiResponse;

    public function show(Request $request)
    {
        $user = $request->user();

        $settings = $user->notificationSetting()->firstOrCreate([], [
            'general_notification' => true,
            'order_updates' => true,
            'promotions_offers' => true,
            'announcements' => true,
            'call_sound' => true,
            'vibration' => true,
            'notification_types' => ['push', 'email'],
        ]);

        return $this->successResponse(
            new NotificationSettingResource($settings),
            'Notification settings retrieved successfully.'
        );
    }

    public function update(NotificationSettingRequest $request)
    {
        $settings = $request->user()->notificationSetting()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated()
        );

        return $this->successResponse(
            new NotificationSettingResource($settings),
            'Notification settings updated successfully.'
        );
    }

}
