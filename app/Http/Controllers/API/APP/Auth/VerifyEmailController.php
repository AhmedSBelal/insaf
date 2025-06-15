<?php

namespace App\Http\Controllers\API\APP\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emails\Verifications\ResendVerifyCodeRequest;
use App\Http\Requests\Emails\Verifications\VerifyCodeRequest;
use App\Models\User;
use App\Services\VerifyEmailService;
use App\Traits\ApiResponse;

class VerifyEmailController extends Controller
{

    use ApiResponse;

    protected $verifyEmailService;

    public function __construct()
    {
        $this->verifyEmailService = new VerifyEmailService();
    }

    public function verify(VerifyCodeRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return $this->failureResponse('User not found.', 404);
        }
        if ($user->hasVerifiedEmail()) {
            return $this->failureResponse('E-mail already verified.', 409);
        }
        if ($user->verifyEmailWithCode($data['code'])) {
            return $this->successResponse([
                'user' => $user
            ], 'E-mail verified.', 200);
        }
        return $this->failureResponse('E-mail not verified.', 500);
    }

    public function resend(ResendVerifyCodeRequest $request)
    {
        $email = $request->validated('email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->failureResponse('User not found.', 404);
        }
        if ($user->hasVerifiedEmail()) {
            return $this->failureResponse('E-mail already verified.', 409);
        }
        if (!$this->verifyEmailService->send($user)) {
            return $this->failureResponse('Verification e-mail not send.');
        }
        return $this->successResponse([
            'email' => $email
        ], 'Verification e-mail send.', 201);
    }

}
