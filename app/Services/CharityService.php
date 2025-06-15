<?php

namespace App\Services;

use App\Enums\CharityStatus;

class CharityService
{

    public function registerCharity($user, $phoneNumber)
    {
        return $user->charity()->create([
            'id'           => $user->id,
            'charity_id'   => $user->id,
            'admin_id'     => null,
            'phone_number' => $phoneNumber,
            'status'       => CharityStatus::Pending->value
        ]);
    }

}
