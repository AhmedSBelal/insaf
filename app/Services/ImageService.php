<?php

namespace App\Services;

use App\Enums\ImageType;

class ImageService
{
    public function storeImage($file, $user, $type)
    {
        $path = $file->store('images', 'public');

        return $user->images()->create([
            'url' => $path,
            'type' => $type
        ]);
    }
}
