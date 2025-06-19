<?php

namespace App\Services;

use Stevebauman\Location\Facades\Location;

class LocationService
{
    public static function storeLocation($location, $user)
    {
        $digitalLocation = Location::get();
        return $user->locations()->create([
            'physical_location' => $location,
            'latitude' => $digitalLocation->latitude ?? null,
            'longitude' => $digitalLocation->longitude ?? null,
        ]);
    }
}
