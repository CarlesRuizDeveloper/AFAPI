<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class ManagerControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_manager_can_create_afa_user()
    {
        // Crear un usuario manager
        $manager = User::factory()->create(['role' => 'manager']);
    
        // Ejecutar la solicitud POST para crear un usuario AFA
        $response = $this->actingAs($manager)->postJson('/api/crear-usuario-afa', [
            'name' => 'AFA User',
            'email' => 'afauser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    
        // Imprimir la respuesta para depurar
        $this->assertEquals(201, $response->status(), 'Código de estado incorrecto');
        print_r($response->getContent());
    
        // Verificar que se ha creado correctamente
        $response->assertStatus(201)->assertJson(['message' => 'Usuari AFA creat correctament']);
    }
    

    public function test_familia_cannot_create_afa_user()
    {

        $familia = User::factory()->create(['role' => 'familia']);

        $response = $this->actingAs($familia)->postJson('/api/crear-usuario-afa', [
            'name' => 'AFA User',
            'email' => 'afauser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(403);
    }

    public function test_afa_cannot_create_afa_user()
    {
        $afa = User::factory()->create(['role' => 'afa']);

        $response = $this->actingAs($afa)->postJson('/api/crear-usuario-afa', [
            'name' => 'AFA User',
            'email' => 'afauser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(403);
    }
}
