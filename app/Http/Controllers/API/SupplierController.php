<?php

namespace App\Http\Controllers\API;

use App\Enums\ImageType;
use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\APP\Auth\SupplierRegisterRequest;
use App\Services\ImageService;
use App\Services\LocationService;
use App\Services\SupplierService;
use App\Services\UserService;
use App\Services\VerifyEmailService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{

    use ApiResponse;
    private $userService;
    private $locationService;
    private $supplierService;
    private $verifyEmailService;


    public function __construct(
        UserService $userService,
        LocationService $locationService,
        SupplierService $supplierService,
        VerifyEmailService $verifyEmailService
    ) {
        $this->userService = $userService;
        $this->locationService = $locationService;
        $this->supplierService = $supplierService;
        $this->verifyEmailService = $verifyEmailService;
    }

    public function supplierRegister(SupplierRegisterRequest $request) {
        DB::beginTransaction();
        try {
            if (!$request->hasFile('commercial_register')) {
                return $this->failureResponse('Commercial Register Not Found');
            }
            if (!$request->hasFile('health_certificate')) {
                return $this->failureResponse('Health Certificate Not Found');
            }

            $data = $request->validated();

            $data['role'] = UserRoles::Supplier->value;
            $user = $this->userService->createUser($data);

            if (!$user) {
                return $this->failureResponse('Something went wrong, try again later');
            }
            $supplier = $this->supplierService->registerSupplier($user, $data['phone_number']);
            ImageService::storeCommercialRegisters($request->file('commercial_register'), $supplier);
            ImageService::storeHealthCertificate($request->file('health_certificate'), $supplier);
            $this->locationService->storeLocation($data['location'], $user);
            if (!$this->verifyEmailService->send($user)) {
                return $this->failureResponse('Something went wrong, try again later');
            }
            DB::commit();
            return $this->successResponse([], 'Registration successful, check your email for the confirmation', 201);
        } catch (\Exception $exception) {
            Log::error("supplier register >> \n\n" . $exception->getMessage() . "\n\n");
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

}
