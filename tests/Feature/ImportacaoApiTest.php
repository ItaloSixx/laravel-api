<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportacaoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_importacao_de_hoteis()
    {
        $response = $this->postJson('/api/v1/import/hotels');
        $response->assertStatus(200);

        $this->assertDatabaseCount('hotels', 3);
        $this->assertDatabaseHas('hotels', ['id' => 1375988, 'name' => 'Hotel Foco Prime']);
        $this->assertDatabaseHas('hotels', ['id' => 1375989, 'name' => 'Hotel Foco Beach']);
        $this->assertDatabaseHas('hotels', ['id' => 1375990, 'name' => 'Hotel Foco Privillege']);
    }

    public function test_importacao_de_quartos()
    {
        $this->postJson('/api/v1/import/hotels');
        
        $response = $this->postJson('/api/v1/import/rooms');
        $response->assertStatus(200);

        $this->assertDatabaseCount('rooms', 3);
        $this->assertDatabaseHas('rooms', ['id' => 137598802, 'hotel_id' => 1375988, 'name' => 'Deluxe Double Room']);
    }

    public function test_importacao_de_tarifas()
    {
        $this->postJson('/api/v1/import/hotels');
        
        $response = $this->postJson('/api/v1/import/rates');
        $response->assertStatus(200);

        $this->assertDatabaseHas('rates', ['hotel_id' => 1375988]);
    }

    public function test_importacao_de_reservas()
    {
        $this->postJson('/api/v1/import/hotels');
        $this->postJson('/api/v1/import/rooms');
        $this->postJson('/api/v1/import/rates');

        $response = $this->postJson('/api/v1/import/reservations');
        $response->assertStatus(200);

        $this->assertDatabaseHas('reservations', ['hotel_id' => 1375988]);
    }

    public function test_reimportacao_nao_duplica_hoteis()
    {
        $this->postJson('/api/v1/import/hotels');
        $this->postJson('/api/v1/import/hotels');

        $this->assertDatabaseCount('hotels', 3);
    }

    public function test_reimportacao_nao_duplica_quartos()
    {
        $this->postJson('/api/v1/import/hotels');
        $this->postJson('/api/v1/import/rooms');
        $this->postJson('/api/v1/import/rooms');

        $this->assertDatabaseCount('rooms', 3);
    }
}
