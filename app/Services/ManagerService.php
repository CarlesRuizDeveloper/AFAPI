<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagerService
{
    /**
     * Crea un usuario AFA
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearUsuarioAfa(array $data)
    {

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'afa',
        ]);

        return response()->json(['message' => 'Usuari AFA creat correctament', 'user' => $user], 201);
    }
}
