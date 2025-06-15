<?php

namespace App\Http\Controllers\API\Supplier;

use App\Enums\ImageType;
use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Supplier\UpdatePasswordRequest;
use App\Http\Requests\API\Supplier\UpdateProfileRequest;
use App\Http\Resources\API\Supplier\ProfileResource;
use App\Models\Image;
use Hash;
use Illuminate\Http\Request;

class ProfileController extends APIBaseController
{
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['supplier' , 'roles']);

        // dd($user);
        // return $user;

        return $this->successResponse(new ProfileResource($user), "Profile retrieved successfully");
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());
        $user->load(['supplier' , 'roles']);

        if($request->hasFile('commercial_register')) {
            $user->supplier->commercialRegisters
            ->where('type', ImageType::CommercialRegister->value)
            ->update(
                [
                    'url' => $this->uploadFile($request->commercial_register,'commercial_registers'),
                    'type' => ImageType::CommercialRegister->value
                ]
            );
        }

        if($request->hasFile('health_certificate')) {
            $user->supplier->healthCertificates()
            ->where('type', ImageType::HealthCertificate->value)
            ->update(
                [
                    'url' => $this->uploadFile($request->health_certificate,'health_certificates'),
                    'type' => ImageType::HealthCertificate->value
                ]
            );
        }

        return $this->successResponse(new ProfileResource($user), "Profile updated successfully");
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->failureResponse("Old password is incorrect", 422);
        }

        $user->update(['password' => bcrypt($request->new_password)]);

        return $this->successResponse(new ProfileResource($user), "Password updated successfully");
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return $this->successResponse([], "Account deleted successfully");
    }

    public function signOutCurrentDevice(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse([], "Signed out from current device successfully");
    }

    public function signOutOtherDevices(Request $request)
    {
        $user = $request->user();
        $tokens = $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->get();

        foreach ($tokens as $token) {
            $token->delete();
        }

        return $this->successResponse([], "Signed out from other devices successfully");
    }

    public function signOutAllDevices(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->successResponse([], "Signed out from all devices successfully");
    }
}
