<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = $this->passwordResetService->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Enllaç de restabliment de contrasenya enviat'], 200);
        }

        return response()->json(['message' => 'No s\'ha pogut enviar l\'enllaç de restabliment'], 400);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $status = $this->passwordResetService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Contrasenya restablerta correctament'], 200);
        }

        return response()->json(['message' => 'Error al restablir la contrasenya'], 400);
    }
}
