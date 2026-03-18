<?php

namespace App\Http\Controllers;

use App\Services\ServicoQuarto;
use App\Http\Requests\StoreQuartoRequest;
use App\Http\Requests\UpdateQuartoRequest;

class QuartoController extends Controller
{
    protected $servicoQuarto;

    public function __construct(ServicoQuarto $servicoQuarto)
    {
        $this->servicoQuarto = $servicoQuarto;
    }

    public function index()
    {
        return response()->json($this->servicoQuarto->listarTodos());
    }

    public function store(StoreQuartoRequest $request)
    {
        $quarto = $this->servicoQuarto->criar($request->validated());
        return response()->json($quarto, 201);
    }

    public function show($id)
    {
        $quarto = $this->servicoQuarto->buscarPorId($id);
        if (!$quarto) {
            return response()->json(['message' => 'Quarto não encontrado.'], 404);
        }
        return response()->json($quarto);
    }

    public function update(UpdateQuartoRequest $request, $id)
    {
        $quarto = $this->servicoQuarto->atualizar($id, $request->validated());
        return response()->json($quarto);
    }

    public function destroy($id)
    {
        $this->servicoQuarto->deletar($id);
        return response()->json(['message' => 'Quarto removido com sucesso.']);
    }
}
