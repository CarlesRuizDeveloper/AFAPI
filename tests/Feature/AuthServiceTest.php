<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class AuthServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'Usuari creat correctament']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token', 'user']);
    }

    public function test_user_can_request_password_reset()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200)->assertJson(['message' => 'Enllaç de restabliment de contrasenya enviat']);
    }

    public function test_user_can_reset_password()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        $response = $this->postJson('/api/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Contrasenya restablerta correctament']);
    }
}
