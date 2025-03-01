<?php

namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerifyEmailService
{
    public function send(User $user) :bool 
    {
        try {
            $user->generateVerificationCode();
            Mail::to($user->email)->send(new VerificationCodeMail($user->email_verification_code));
        } catch (\Exception $e) {
            Log::error("send email verification >> \n\n" . $e->getMessage());
            return false;
        }
        return true;
    }

}
