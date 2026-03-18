<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Hotel;
use App\Models\Quarto;

class QuartoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_quarto_na_api()
    {
        Hotel::create(['id' => 1, 'name' => 'Teste']);
        
        $response = $this->postJson('/api/v1/rooms', [
            'id' => 999,
            'hotel_id' => 1,
            'name' => 'Suite Master',
            'inventory_count' => 5
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Suite Master']);
                 
        $this->assertDatabaseHas('rooms', ['id' => 999]);
    }

    public function test_pode_listar_quartos_na_api()
    {
        Hotel::create(['id' => 1, 'name' => 'Teste']);
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Quarto Teste', 'inventory_count' => 1]);

        $response = $this->getJson('/api/v1/rooms');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Quarto Teste']);
    }
}
