<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Hotel;
use App\Models\Quarto;
use App\Models\Reserva;

class ReservaApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Hotel::create(['id' => 1, 'name' => 'Hotel Test']);
        Hotel::create(['id' => 2, 'name' => 'Hotel Outro']);
        Quarto::create(['id' => 100, 'hotel_id' => 1, 'name' => 'Quarto 1', 'inventory_count' => 1]);
        Quarto::create(['id' => 200, 'hotel_id' => 2, 'name' => 'Quarto Hotel 2', 'inventory_count' => 1]);
    }

    private function reservaPayload(array $overrides = []): array
    {
        return array_merge([
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
        ], $overrides);
    }

    public function test_pode_criar_reserva_api()
    {
        $response = $this->postJson('/api/v1/reservations', $this->reservaPayload());

        $response->assertStatus(201)
                 ->assertJsonFragment(['customer_first_name' => 'John']);
                 
        $this->assertDatabaseHas('reservations', ['id' => 99]);
    }

    public function test_rejeita_reserva_com_conflito_de_datas_api()
    {
        $this->postJson('/api/v1/reservations', $this->reservaPayload());

        // Tenta criar outra no mesmo período
        $response = $this->postJson('/api/v1/reservations', $this->reservaPayload([
            'id' => 100,
            'roomreservation_id' => 12346,
        ]));

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'O quarto não está disponível para o período solicitado (conflito de datas).']);
    }

    public function test_pode_listar_reservas_api()
    {
        $this->postJson('/api/v1/reservations', $this->reservaPayload());

        $response = $this->getJson('/api/v1/reservations');

        $response->assertStatus(200)
                 ->assertJsonFragment(['customer_first_name' => 'John']);
    }

    public function test_rejeita_reserva_com_room_de_outro_hotel()
    {
        // room_id 200 pertence ao hotel 2, mas estamos enviando hotel_id 1
        $response = $this->postJson('/api/v1/reservations', $this->reservaPayload([
            'room_id' => 200,
        ]));

        $response->assertStatus(422);
    }
}
