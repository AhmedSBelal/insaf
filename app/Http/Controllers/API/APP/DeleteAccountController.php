<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteAccountController extends Controller
{
    use ApiResponse;

    public function destroy() {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            // remove profile_image
            ImageService::deleteImage($user->profileImage);
            // remove commercial register image
            ImageService::deleteCommercialRegisters($user);
            // remove charity
            $user->charity()->delete();
            // remove notifications
            $user->notifications()->delete();
            // remove location
            $user->locations()->delete();
            // remove roles and permissions
            $user->roles()->detach();
            $user->permissions()->detach();
            // remove user
            $user->delete();
            DB::commit();
            return $this->successResponse(null, 'Account deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('delete account failed: ' . $e->getMessage());
            return $this->failureResponse('Try again later', 500);
        }
    }

}
