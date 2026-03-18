<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Hotel;
use App\Models\Quarto;

class ReservaApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Hotel::create(['id' => 1, 'name' => 'Hotel Test']);
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Quarto 1', 'inventory_count' => 1]);
    }

    public function test_pode_criar_reserva_api()
    {
        $payload = [
            'id' => 99,
            'hotel_id' => 1,
            'room_id' => 100,
            'roomreservation_id' => 12345,
            'customer_first_name' => 'John',
            'customer_last_name' => 'Doe',
            'arrival_date' => '2026-05-01',
            'departure_date' => '2026-05-05',
            'guest_count' => 2,
            'guest_type' => 'A',
            'currency_code' => 'BRL',
            'total_price' => 500.00,
            'date' => '2026-01-01',
            'time' => '12:00:00'
        ];

        $response = $this->postJson('/api/reservations', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['customer_first_name' => 'John']);
                 
        $this->assertDatabaseHas('reservations', ['id' => 99]);
    }

    public function test_rejeita_reserva_com_conflito_de_datas_api()
    {
        $payload = [
            'id' => 99,
            'hotel_id' => 1,
            'room_id' => 100,
            'roomreservation_id' => 12345,
            'customer_first_name' => 'John',
            'customer_last_name' => 'Doe',
            'arrival_date' => '2026-05-01',
            'departure_date' => '2026-05-05',
            'guest_count' => 2,
            'guest_type' => 'A',
            'currency_code' => 'BRL',
            'total_price' => 500.00,
            'date' => '2026-01-01',
            'time' => '12:00:00'
        ];

        $this->postJson('/api/reservations', $payload);

        // Tenta criar outra no mesmo período
        $payload['id'] = 100;
        $payload['roomreservation_id'] = 12346;
        $response = $this->postJson('/api/reservations', $payload);

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'O quarto não está disponível para o período solicitado (conflito de datas).']);
    }
}
