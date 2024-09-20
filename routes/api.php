<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum', 'role:familia'])->group(function () {
    Route::post('/anuncios', function () {
        return response()->json(['message' => 'Acceso permitido para el rol familia'], 200);//Provisional
    });
});

Route::middleware(['auth:sanctum', 'role:afa'])->group(function () {
    Route::post('/gestionar-anuncios', function () {
        return response()->json(['message' => 'Acceso permitido para el rol AFA'], 200);//Provisional
    });
});

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::post('/crear-usuario-afa', function () {
        return response()->json(['message' => 'Acceso permitido para el rol Manager'], 200);//Provisional
    });
});

