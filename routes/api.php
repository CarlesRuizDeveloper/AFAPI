<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas API para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider y estarán
| bajo el grupo de middleware "api".
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas con middleware para cada rol
Route::middleware(['auth:sanctum', 'role:familia'])->group(function () {
    Route::post('/anuncios', function () {
        return response()->json(['message' => 'Acceso permitido para el rol familia'], 200);
    });
});

Route::middleware(['auth:sanctum', 'role:afa'])->group(function () {
    Route::post('/gestionar-anuncios', function () {
        return response()->json(['message' => 'Acceso permitido para el rol AFA'], 200);
    });
});

// Corregir la ruta del manager
Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::post('/crear-usuario-afa', [ManagerController::class, 'crearUsuarioAfa']);
});
