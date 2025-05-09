<?php

namespace App\Http\Controllers\API;

use App\Enums\ImageType;
use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\APP\Auth\CharityRegisterRequest;
use App\Services\CharityService;
use App\Services\ImageService;
use App\Services\LocationService;
use App\Services\UserService;
use App\Services\VerifyEmailService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CharityController extends Controller
{
    use ApiResponse;
    private $userService;
    private $CharityService;
    private $verifyEmailService;


    public function __construct(
        UserService $userService,
        CharityService $CharityService,
        VerifyEmailService $verifyEmailService
    ) {
        $this->userService = $userService;
        $this->CharityService = $CharityService;
        $this->verifyEmailService = $verifyEmailService;
    }

    public function charityRegister(CharityRegisterRequest $request) {
        DB::beginTransaction();
        try {
            if (!$request->hasFile('commercial_register')) {
                return $this->failureResponse('Commercial Register Not Found');
            }

            $data = $request->validated();

            $data['role'] = UserRoles::Charity->value;
            $user = $this->userService->createUser($data);

            if (!$user) {
                return $this->failureResponse('Something went wrong, try again later 0');
            }
            $charity = $this->CharityService->registerCharity($user, $data['phone_number']);
            if (!$charity) {
                return $this->failureResponse('Something went wrong, try again later 1');
            }

            ImageService::storeCommercialRegisters($request->file('commercial_register'), $charity);
            LocationService::storeLocation($data['location'], $charity);
            if (!$this->verifyEmailService->send($user)) {
                return $this->failureResponse('Something went wrong, try again later 2');
            }
            DB::commit();
            return $this->successResponse([], 'Registration successful, check your email for the confirmation', 201);
        } catch (\Exception $exception) {
            Log::error("charity register >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later 3');
        }
    }
}
