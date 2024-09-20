<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'familia', 
        ]);
    
        return response()->json(['message' => 'Usuari creat correctament'], 201);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result) {
            return response()->json(['message' => 'Credencials incorrectes'], 401);
        }

        return response()->json(['token' => $result['token'], 'user' => $result['user']], 200);
    }

    public function logout()
    {
        $this->authService->logout(Auth::user());

        return response()->json(['message' => 'Sessió tancada correctament'], 200);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = $this->authService->sendResetLink($request->email);

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Enllaç de restabliment de contrasenya enviat']);
        }

        throw ValidationException::withMessages(['email' => [__($status)]]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = $this->authService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'));

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Contrasenya restablerta correctament']);
        }

        return response()->json(['message' => __($status)], 400);
    }
}
