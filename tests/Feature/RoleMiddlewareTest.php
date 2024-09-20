<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_familia_user_can_access_familia_routes()
    {
        $user = User::factory()->create(['role' => 'familia']);

        $response = $this->actingAs($user)->postJson('/api/anuncios');
        $response->assertStatus(200); 
    }

    public function test_familia_user_cannot_access_afa_routes()
    {
        $user = User::factory()->create(['role' => 'familia']);

        $response = $this->actingAs($user)->postJson('/api/gestionar-anuncios');
        $response->assertStatus(403); 
    }

    public function test_afa_user_can_access_afa_routes()
    {
        $user = User::factory()->create(['role' => 'afa']);

        $response = $this->actingAs($user)->postJson('/api/gestionar-anuncios');
        $response->assertStatus(200); 
    }
    
}

