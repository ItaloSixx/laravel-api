<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Hotel;
use App\Models\Quarto;

class QuartoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Hotel::create(['id' => 1, 'name' => 'Hotel Teste']);
    }

    public function test_pode_criar_quarto_na_api()
    {
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
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Quarto Teste', 'inventory_count' => 1]);

        $response = $this->getJson('/api/v1/rooms');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Quarto Teste']);
    }

    public function test_pode_buscar_quarto_por_id()
    {
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Quarto Show', 'inventory_count' => 3]);

        $response = $this->getJson('/api/v1/rooms/100');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Quarto Show']);
    }

    public function test_pode_atualizar_quarto()
    {
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Antigo', 'inventory_count' => 2]);

        $response = $this->putJson('/api/v1/rooms/100', [
            'name' => 'Novo Nome',
            'inventory_count' => 10
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Novo Nome']);
                 
        $this->assertDatabaseHas('rooms', ['id' => 100, 'name' => 'Novo Nome', 'inventory_count' => 10]);
    }

    public function test_pode_deletar_quarto()
    {
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Para Deletar', 'inventory_count' => 1]);

        $response = $this->deleteJson('/api/v1/rooms/100');
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Quarto removido com sucesso.']);
                 
        $this->assertDatabaseMissing('rooms', ['id' => 100]);
    }

    public function test_retorna_404_para_quarto_inexistente()
    {
        $response = $this->getJson('/api/v1/rooms/99999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Recurso não encontrado.']);
    }

    public function test_retorna_422_para_payload_invalido()
    {
        $response = $this->postJson('/api/v1/rooms', [
            // missing required fields
        ]);

        $response->assertStatus(422);
    }
}
