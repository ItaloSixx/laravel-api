<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportacaoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_importacao_de_hoteis()
    {
        $response = $this->postJson('/api/import/hotels');
        $response->assertStatus(200);
    }

    public function test_importacao_de_quartos()
    {
        $this->postJson('/api/import/hotels');
        
        $response = $this->postJson('/api/import/rooms');
        $response->assertStatus(200);
    }

    public function test_importacao_de_tarifas()
    {
        $this->postJson('/api/import/hotels');
        
        $response = $this->postJson('/api/import/rates');
        $response->assertStatus(200);
    }

    public function test_importacao_de_reservas()
    {
        $this->postJson('/api/import/hotels');
        $this->postJson('/api/import/rooms');
        $this->postJson('/api/import/rates');

        $response = $this->postJson('/api/import/reservations');
        $response->assertStatus(200);
    }
}
