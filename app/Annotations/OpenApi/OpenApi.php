<?php

namespace App\Annotations\OpenApi;

/**
 * @OA\Info(
 *     title="AFAPI Documentation",
 *     version="1.0.0",
 *     description="API documentation for the AFAPI project",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Introduce el token de autenticación en el formato 'Bearer {token}'",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum",
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Localhost API server"
 * )
 */
class OpenApi
{
    // This class can be empty. It just holds the OpenAPI annotations.
}
