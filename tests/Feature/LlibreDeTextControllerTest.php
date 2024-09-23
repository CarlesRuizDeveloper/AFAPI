<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\LlibreDeText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LlibreDeTextControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_can_list_all_llibres()
    {
        LlibreDeText::factory()->count(3)->create();

        $response = $this->getJson('/api/llibredetext');

        $response->assertStatus(200);

        $response->assertJsonCount(3);
    }

    public function test_it_can_create_a_llibre()
    {
        $user = User::factory()->create();  
        $token = $user->createToken('TestToken')->plainTextToken;  
    
        $data = [
            'titol' => 'Matemàtiques per a tothom',
            'curs' => '2n Primària',
            'editorial' => 'Anaya',
            'observacions' => 'Amb activitats divertides',
        ];
    
        
        $response = $this->postJson('/api/llibredetext', $data, [
            'Authorization' => 'Bearer ' . $token,  
        ]);
    
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Llibre creat correctament',
        ]);
    
        $this->assertDatabaseHas('llibres_de_text', $data);
    }
 
    public function test_it_can_update_a_llibre()
    {
        $user = User::factory()->create();  
        $token = $user->createToken('TestToken')->plainTextToken; 
    
        $llibre = LlibreDeText::factory()->create();  
    
        $updateData = [
            'titol' => 'Títol actualitzat',
            'editorial' => 'Editorial actualitzada',
            'observacions' => 'Observacions actualitzades',
        ];
    
        $response = $this->putJson("/api/llibredetext/{$llibre->id}", $updateData, [
            'Authorization' => 'Bearer ' . $token,  
        ]);
    
        $response->assertStatus(200);
    
        $this->assertDatabaseHas('llibres_de_text', array_merge(['id' => $llibre->id], $updateData));
    }

    public function test_it_can_delete_a_llibre()
    {
        $user = User::factory()->create();  
        $token = $user->createToken('TestToken')->plainTextToken; 
    
        $llibre = LlibreDeText::factory()->create(); 
    
        $response = $this->deleteJson("/api/llibredetext/{$llibre->id}", [], [
            'Authorization' => 'Bearer ' . $token, 
        ]);
    
        $response->assertStatus(200);
    
        $this->assertDatabaseMissing('llibres_de_text', ['id' => $llibre->id]);
    }
    
}
