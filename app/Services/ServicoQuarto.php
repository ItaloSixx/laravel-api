<?php

namespace App\Services;

use App\Models\Quarto;
use Illuminate\Database\Eloquent\Collection;

class ServicoQuarto
{
    /**
     * Lista todos os quartos
     */
    public function listarTodos(): Collection
    {
        return Quarto::all();
    }

    /**
     * Busca um quarto específico pelo ID
     */
    public function buscarPorId($id): Quarto
    {
        return Quarto::findOrFail($id);
    }

    /**
     * Cria um novo quarto
     */
    public function criar(array $dados): Quarto
    {
        return Quarto::create($dados);
    }

    /**
     * Atualiza os dados de um quarto existente
     */
    public function atualizar($id, array $dados): Quarto
    {
        $quarto = Quarto::findOrFail($id);
        $quarto->update($dados);
        return $quarto;
    }

    /**
     * Remove um quarto
     */
    public function deletar($id): bool
    {
        $quarto = Quarto::findOrFail($id);
        return $quarto->delete();
    }
}
