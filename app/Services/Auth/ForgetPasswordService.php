<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Random\RandomException;

class ForgetPasswordService
{
    protected User $user;

    /**
     * @param ForgetPasswordRequest $request
     * @return int
     * @throws RandomException
     */
    public function request(ForgetPasswordRequest $request): int
    {
        if (!$user = User::where(['email' => $request->only('email')])->first()) {
            return 0;
        }

//        $otp = random_int(1000, 9999);
        $otp = 1111; // static for now
        $user->update(['otp' => $otp]);

//        to::do NotificationService::sendEmail($user, ['msg' => 'reset_pw_otp', 'title' => 'reset_pw_title', 'otp' => $otp]);

        return $otp;
    }

    /**
     * @param ResetPasswordRequest $request
     * @return boolean
     */
    public function reset(ResetPasswordRequest $request): bool
    {
        if (!$user = User::where(['email' => $request->email, 'otp' => $request->otp])->first()) {
            return false;
        }

        $user->update(['password' => $request->password, 'otp' => null]);

        return true;
    }
}
