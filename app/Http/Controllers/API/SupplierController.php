<?php

namespace App\Http\Controllers\API;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\SupplierRegisterRequest;
use App\Services\ImageService;
use App\Services\LocationService;
use App\Services\SupplierService;
use App\Services\UserService;
use App\Services\VerifyEmailService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{

    use ApiResponse;
    private $userService;
    private $imageService;
    private $locationService;
    private $supplierService;

    private $verifyEmailService;


    public function __construct(
        UserService $userService,
        ImageService $imageService,
        LocationService $locationService,
        SupplierService $supplierService, 
        VerifyEmailService $verifyEmailService
    ) {
        $this->userService = $userService;
        $this->imageService = $imageService;
        $this->locationService = $locationService;
        $this->supplierService = $supplierService;
        $this->verifyEmailService = $verifyEmailService;
    }

    public function supplierRegister(SupplierRegisterRequest $request) {
        try {
            if (!$request->hasFile('commercial_register')) {
                return $this->failureResponse('Commercial Register Not Found');
            }

            $data = $request->validated();
            
            $data['role'] = UserRoles::Supplier->value;
            $user = $this->userService->createUser($data);

            if (!$user) {
                return $this->failureResponse('Something went wrong, try again later');
            }

            $this->supplierService->registerSupplier($user, $data['phone_number']);
            $this->imageService->storeImage($request->file('commercial_register'), $user);
            $this->locationService->storeLocation($data['location'], $user);
            if (!$this->verifyEmailService->send($user)) {
                return $this->failureResponse('Somethingff went wrong, try again later');
            }
            return $this->successResponse([], 'Registration successful, check your email for the confirmation link', 201);
        } catch (\Exception $exception) {
            Log::error("supplier register >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

}
