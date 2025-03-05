<?php

namespace App\Services;

class LocationService
{
    public function storeLocation($location, $user)
    {
        return $user->locations()->create([
            'physical_location' => $location
        ]);
    }
}
