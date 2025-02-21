<?php

namespace App\Services;

use App\Enums\ImageType;

class ImageService
{
    public function storeImage($file, $user)
    {
        $path = $file->store('images', 'public');

        return $user->images()->create([
            'url' => $path,
            'type' => ImageType::CommercialRegister
        ]);
    }
}
