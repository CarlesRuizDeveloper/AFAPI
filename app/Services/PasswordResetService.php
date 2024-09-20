<?php

namespace App\Services;

use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink(array $credentials)
    {
        return Password::sendResetLink($credentials);
    }

    public function resetPassword(array $credentials, callable $callback)
    {
        return Password::reset($credentials, $callback);
    }
}
