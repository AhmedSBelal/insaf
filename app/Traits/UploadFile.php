<?php

namespace App\Traits;

trait UploadFile
{
    public function uploadFile($file, $path) {
        $filename = \Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($path, $filename, 'public');

        return $path . '/' . $filename;
    }

}
