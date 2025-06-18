<?php

namespace App\Services;

use App\Enums\ImageType;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public static function storeCommercialRegisters($file, $user) {
        $path = $file->store('images', 'public');
        return $user->commercialRegisters()->create([
            'url' => $path,
            'type' => ImageType::CommercialRegister->value,
        ]);
    }

//    public static function updateCommercialRegisters($file, $user)
//    {
//        try {
//            // Find existing record
//            $existing = $user->commercialRegisters()
//                ->where('id', $user->id)
//                ->firstOrFail();
//
//            // Store new file
//            $newPath = $file->store('images', 'public');
//
//            // Update record
//            $existing->update([
//                'url' => $newPath,
//                'updated_at' => now(),
//            ]);
//
//            // Delete old file
//            Storage::disk('public')->delete($existing->url);
//
//            return $existing->fresh();
//
//        } catch (\Exception $e) {
//            // Clean up if file was stored but transaction failed
//            if (isset($newPath)) {
//                Storage::disk('public')->delete($newPath);
//            }
//            Log::error("Commercial register update failed: " . $e->getMessage());
//            throw $e;
//        }
//    }

    public static function deleteCommercialRegisters($user)
    {
        try {
            $record = $user->commercialRegisters()->first();

            if ($record) {
                // Delete the file from storage
                Storage::disk('public')->delete($record->url);

                // Delete the record from DB
                $record->delete();
            }
            return true;

        } catch (\Exception $e) {
            Log::error("Commercial register deletion failed: " . $e->getMessage());
            throw $e;
        }
    }


    public static function storeHealthCertificate($file, $user) {
        $path = $file->store('images', 'public');
        return $user->healthCertificates()->create([
            'url' => $path,
            'type' => ImageType::HealthCertificate->value,
        ]);
    }

    public static function updateHealthCertificate($file, $user) {

    }

    public static function deleteHealthCertificate($user) {
        try {
            $record = $user->healthCertificates()->first();

            if ($record) {
                // Delete the file from storage
                Storage::disk('public')->delete($record->url);

                // Delete the record from DB
                $record->delete();
            }
            return true;

        } catch (\Exception $e) {
            Log::error("Health Certificate deletion failed: " . $e->getMessage());
            throw $e;
        }

    }

    public static function storeProfileImage($file, User $user) {
        $path = $file->store('images', 'public');
        return $user->profileImage()->create([
            'url' => $path,
            'type' => ImageType::Profile->value,
        ]);
    }

    public static function deleteImage($image): bool
    {
        if (!$image) {
            return true; // No image to delete is not an error
        }

        try {
            Storage::disk('public')->delete($image->url);
            return $image->delete();
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error("Failed to delete image: " . $e->getMessage());
            return false;
        }
    }

    public static function updateProfileImage($file, User $user)
    {
        // Delete existing image if it exists
        if ($user->profileImage && !self::deleteImage($user->profileImage)) {
            return false;
        }

        try {
            $path = $file->store('images', 'public');

            $user->profileImage()->create([
                'url' => $path,
                'type' => ImageType::Profile->value,
            ]);

            return true;
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error("Failed to update profile image: " . $e->getMessage());
            return false;
        }
    }

}
