<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\LlibreDeTextController;
use App\Http\Controllers\ChatController;

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
Route::get('/llibredetext/{id}', [LlibreDeTextController::class, 'show']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);//pdte de configurar mails pero pasan test
Route::post('/reset-password', [PasswordResetController::class, 'reset']);//pdte de configurar mails pero pasan test
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);//pdte de configurar mails pero pasan test
})->name('password.reset');
Route::get('/llibredetext', [LlibreDeTextController::class, 'index']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/llibredetext', [LlibreDeTextController::class, 'store']);
    Route::put('/llibredetext/{id}', [LlibreDeTextController::class, 'update']);
    Route::delete('/llibredetext/{id}', [LlibreDeTextController::class, 'destroy']);
    Route::post('/chat/create', [ChatController::class, 'createChat']);
    Route::post('/message/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/chats/{chat_id}/messages', [ChatController::class, 'getMessages']);

});

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

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::post('/crear-usuario-afa', [ManagerController::class, 'crearUsuarioAfa']);
});

