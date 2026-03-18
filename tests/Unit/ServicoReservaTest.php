<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reserva;
use App\Models\Quarto;
use App\Models\Hotel;
use App\Services\ServicoReserva;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServicoReservaTest extends TestCase
{
    use RefreshDatabase;

    protected ServicoReserva $servico;
    protected int $roomId = 100;

    protected function setUp(): void
    {
        parent::setUp();
        $this->servico = new ServicoReserva();

        //Criar dados base
        Hotel::create(['id' => 1, 'name' => 'Hotel Test']);
        Quarto::create(['id' => $this->roomId, 'hotel_id' => 1, 'name' => 'Quarto 1', 'inventory_count' => 1]);
    }

    private function criarReservaExistente(string $checkin, string $checkout)
    {
        Reserva::create([
            'id' => random_int(1, 9999),
            'hotel_id' => 1,
            'room_id' => $this->roomId,
            'roomreservation_id' => random_int(1000, 9999),
            'customer_first_name' => 'John',
            'customer_last_name' => 'Doe',
            'arrival_date' => $checkin,
            'departure_date' => $checkout,
            'guest_count' => 1,
            'guest_type' => 'A',
            'currency_code' => 'BRL',
            'total_price' => 100.00,
            'date' => now()->toDateString(),
            'time' => now()->toTimeString()
        ]);
    }

    public function test_quarto_disponivel_sem_reservas()
    {
        $disponivel = $this->servico->verificarDisponibilidade($this->roomId, '2026-04-10', '2026-04-12');
        $this->assertTrue($disponivel);
    }

    public function test_quarto_indisponivel_com_sobreposicao_total()
    {
        $this->criarReservaExistente('2026-04-10', '2026-04-15');
        
        $disponivel = $this->servico->verificarDisponibilidade($this->roomId, '2026-04-10', '2026-04-15');
        $this->assertFalse($disponivel);
    }

    public function test_quarto_disponivel_em_datas_adjacentes()
    {
        $this->criarReservaExistente('2026-04-10', '2026-04-12');
        
        //Novo check-in no mesmo dia do check-out existente (permitido)
        $disponivel = $this->servico->verificarDisponibilidade($this->roomId, '2026-04-12', '2026-04-14');
        $this->assertTrue($disponivel);

        //Novo check-out no mesmo dia do check-in existente (permitido)
        $disponivel2 = $this->servico->verificarDisponibilidade($this->roomId, '2026-04-08', '2026-04-10');
        $this->assertTrue($disponivel2);
    }

    public function test_quarto_indisponivel_com_sobreposicao_parcial()
    {
        $this->criarReservaExistente('2026-04-10', '2026-04-20');
        
        //Check-in antes, check-out durante
        $this->assertFalse($this->servico->verificarDisponibilidade($this->roomId, '2026-04-08', '2026-04-12'));

        //Check-in durante, check-out depois
        $this->assertFalse($this->servico->verificarDisponibilidade($this->roomId, '2026-04-15', '2026-04-25'));
        
        //Totalmente contido na reserva existente
        $this->assertFalse($this->servico->verificarDisponibilidade($this->roomId, '2026-04-12', '2026-04-14'));
    }
}
