<?php

namespace App\Http\Controllers\API\APP\Auth\ForgetPassword;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDashboard\forgetpassword\ForgetPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    use ApiResponse;
    /**
     * Handle the incoming request.
     */
    public function __invoke(ForgetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if (!User::where('email', $data['email'])->exists()) {
                return $this->failureResponse('This emails is not exists in our system.', 200);
            }

            // Delete all old code that the user sent before.
            ResetCodePassword::where('email', $request->email)->delete();

            // Generate random code
            $data['code'] = mt_rand(100000, 999999);

            // Create a new code
            $codeData = ResetCodePassword::create($data);

            // Send email to user
            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

            DB::commit();
            return $this->successResponse([], 'Code has been sent to your email.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Forgot password failed: ' . $exception->getMessage());
            return $this->failureResponse('Something went wrong, please try again later.');
        }
    }
}
