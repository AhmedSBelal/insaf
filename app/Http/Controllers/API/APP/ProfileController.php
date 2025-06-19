<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Http\Requests\APP\ProfileRequest;
use App\Http\Resources\APP\ProfileResource;
use App\Services\ImageService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponse;

    public function update(ProfileRequest $request) {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $user = auth()->user();
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // Update charity phone if exists
            if ($user->charity) {
                $user->charity()->update(['phone_number' => $data['phone']]);
            }

            // Handle commercial register
            if ($request->hasFile('commercial_register')) {
                $this->updateCommercialRegister($user, $request->file('commercial_register'));
            }

            // Handle profile image
            if ($request->hasFile('profile_image')) {
                if (!ImageService::updateProfileImage($request->file('profile_image'), $user)) {
                    return $this->failureResponse('Something went wrong, try again later');
                }
            }

            DB::commit();
            return $this->successResponse(
                new ProfileResource($user->fresh()->load(['charity'])),
                'Profile updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['commercial_register', 'profile_image'])
            ]);
            return $this->failureResponse(
                'Failed to update profile. Please try again.',
                500
            );
        }
    }

    protected function updateCommercialRegister($user, $file)
    {
        if ($user->charity && $user->charity->commercialRegisters) {
            Storage::disk('public')->delete($user->charity->commercialRegisters->url);
            $user->charity->commercialRegisters()->delete();
        }
        $charity = $user->charity;
        ImageService::storeCommercialRegisters($file, $charity);
    }

}
