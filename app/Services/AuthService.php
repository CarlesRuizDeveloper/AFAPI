<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthService
{
    public function register($data)
    {
        $this->validateRegistration($data);
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'familia',
        ]);
    }

    public function login($data)
    {
        $this->validateLogin($data);

        $key = $this->getRateLimitKey(request());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return [
                'error' => true,
                'status' => 429,
                'message' => "Massa intents d'inici de sessió. Torna-ho a intentar en $seconds segons.",
            ];
        }

        if (!Auth::attempt($data)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'Credencials incorrectes.',
            ]);
        }

        RateLimiter::clear($key);

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
    }

    public function sendResetLink($data)
    {
        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }
    }

    public function resetPassword($data)
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }
    }

    protected function validateRegistration($data)
    {
        Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',   
                'regex:/[@$!%*?&]/' 
            ]
        ], [
            'password.regex' => 'La contrasenya ha de tenir almenys 8 caràcters, incloent una majúscula, una minúscula, un número i un símbol especial.',
        ])->validate();
    }

    protected function validateLogin($data)
    {
        Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->validate();
    }

    protected function getRateLimitKey(Request $request)
    {
        return 'login-attempts:' . $request->ip();
    }
}
