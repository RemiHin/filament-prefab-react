<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user): void
    {
        if (empty($user->password)) {
            $token = Password::createToken($user);

            $user->sendSetPasswordNotification($token);

            // User must have a password, else log-in throws a hard exception when validating password
            $user->update(['password_token' => Hash::make(Str::random(32))]);
        }
    }
}
