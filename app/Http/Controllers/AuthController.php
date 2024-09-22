<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $this->authService->register($request->all());

            return response()->json(['message' => 'Usuari creat correctament'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

        if (isset($result['error']) && $result['error']) {
            return response()->json(['message' => $result['message']], $result['status']);
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
        try {
            $this->authService->sendResetLink($request->only('email'));

            return response()->json(['message' => 'Enllaç de restabliment de contrasenya enviat'], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function reset(Request $request)
    {
        try {
            $this->authService->resetPassword($request->all());

            return response()->json(['message' => 'Contrasenya restablerta correctament'], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
