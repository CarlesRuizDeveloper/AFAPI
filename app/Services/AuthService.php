<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthService
{
    // Registrar usuario
    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'familia', // Rol por defecto
        ]);

        return $user;
    }

    // Iniciar sesión
    public function login($data)
    {
        if (!Auth::attempt($data)) {
            return null; 
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    // Cerrar sesión
    public function logout($user)
    {
        $user->tokens()->delete();
    }

    public function sendResetLink($email)
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status;
    }

    public function resetPassword($data)
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status;
    }
}
