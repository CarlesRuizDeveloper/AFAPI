<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Password;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'Usuari creat correctament']);
    }

    public function test_user_cannot_register_with_weak_password()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'weakpassword',
            'password_confirmation' => 'weakpassword',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('Password@123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'Password@123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token', 'user']);
    }

    public function test_user_cannot_login_after_too_many_attempts()
    {
        // Simulamos 5 intentos fallidos
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'testuser@example.com',
                'password' => 'wrongpassword',
            ]);
        }
    
        // Sexto intento, debería devolver un código 429
        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword',
        ]);
    
        // Comprobamos que la respuesta sea 429
        $response->assertStatus(429);
    
        // Comprobamos que el mensaje contiene el fragmento "Massa intents d'inici de sessió"
        $this->assertStringContainsString('Massa intents d\'inici de sessió', $response->json('message'));
    }
    

    public function test_user_can_request_password_reset()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200)->assertJson(['message' => 'Enllaç de restabliment de contrasenya enviat']);
    }

    public function test_user_can_reset_password()
    {
        $user = User::factory()->create(['email' => 'testuser@example.com']);

        $token = Password::createToken($user);

        $response = $this->postJson('/api/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword@123',
            'password_confirmation' => 'NewPassword@123',
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Contrasenya restablerta correctament']);
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create(['email' => 'testuser@example.com']);

        $response = $this->postJson('/api/reset-password', [
            'token' => 'invalid_token',
            'email' => $user->email,
            'password' => 'NewPassword@123',
            'password_confirmation' => 'NewPassword@123',
        ]);

        // Ajustamos el mensaje de error para coincidir con el que está devolviendo la API
        $response->assertStatus(400)->assertJson(['message' => 'Error al restablir la contrasenya']);
    }
}
