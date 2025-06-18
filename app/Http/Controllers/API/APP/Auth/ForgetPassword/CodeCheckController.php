<?php

namespace App\Http\Controllers\API\APP\Auth\ForgetPassword;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDashboard\forgetpassword\CodeCheckToResetPasswordRequest;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CodeCheckController extends Controller
{
    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(CodeCheckToResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            // find the code
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            if (!$passwordReset) {
                return $this->failureResponse(trans('passwords.code_not_found'), 404);
            }

            //Check if it has not expired: the time is one hour
            if ($passwordReset->created_at->addHour()->lt(now())) {
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }

            // find user's email
            $user = User::firstWhere('email', $passwordReset->email);

            if (!$user) {
                return $this->failureResponse(trans('passwords.user_not_found'), 404);
            }

            // Delete tokens BEFORE password update as security measure
            $user->tokens()->delete();

            // update user password
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            // delete current code
            $passwordReset->delete();

            return $this->successResponse($user->only(['id', 'name', 'email']), 'password has been successfully reset');

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Code check failed: ' . $exception->getMessage());
            return $this->failureResponse('Something went wrong, please try again later.');
        }
    }
}
