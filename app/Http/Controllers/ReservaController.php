<?php

namespace App\Http\Controllers;

use App\Services\ServicoReserva;
use App\Http\Requests\StoreReservaRequest;

use InvalidArgumentException;

class ReservaController extends Controller
{
    protected $servicoReserva;

    public function __construct(ServicoReserva $servicoReserva)
    {
        $this->servicoReserva = $servicoReserva;
    }

    public function index()
    {
        return response()->json($this->servicoReserva->listarTodas());
    }

    public function store(StoreReservaRequest $request)
    {
        try {
            $reserva = $this->servicoReserva->criar($request->validated());
            return response()->json($reserva, 201);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
