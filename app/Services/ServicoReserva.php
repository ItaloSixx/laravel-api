<?php

namespace App\Services;

use App\Models\Reserva;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;

class ServicoReserva
{
    /**
     * Lista todas as reservas e seus preços
     */
    public function listarTodas(): Collection
    {
        return Reserva::with('precos')->get();
    }

    /**
     * Cria uma nova reserva se o quarto estiver disponível
     * 
     * @throws InvalidArgumentException
     */
    public function criar(array $dados): Reserva
    {
        if (!$this->verificarDisponibilidade($dados['room_id'], $dados['arrival_date'], $dados['departure_date'])) {
            throw new InvalidArgumentException("O quarto não está disponível para o período solicitado (conflito de datas).");
        }

        return Reserva::create($dados);
    }

    /**
     * Verifica a disponibilidade do quarto (imede conflito de datas de checkin e checkout)
     * Adjacencias são permitidas (exemplo: pessoa sai dia 10, outra entra dia 10)
     */
    public function verificarDisponibilidade(int $roomId, string $arrivalDate, string $departureDate): bool
    {
        //Se já existe uma reserva que cruze as datas, o quarto está indisponível.
        //O cruzamento ocorre quando: a data de entrada desejada < que a saída existente E
        //a data de saída desejada > que a entrada existente.
        $conflito = Reserva::where('room_id', $roomId)
            ->where('arrival_date', '<', $departureDate)
            ->where('departure_date', '>', $arrivalDate)
            ->exists();

        return !$conflito;
    }
}
