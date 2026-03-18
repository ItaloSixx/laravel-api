<?php

namespace App\Http\Controllers;

use App\Services\ServicoImportacaoXml;

class ImportacaoController extends Controller
{
    protected $servicoImportacao;

    public function __construct(ServicoImportacaoXml $servicoImportacao)
    {
        $this->servicoImportacao = $servicoImportacao;
    }

    public function importarHoteis()
    {
        try {
            $this->servicoImportacao->importarHoteis();
            return response()->json(['message' => 'Hotéis importados com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao importar hotéis: ' . $e->getMessage()], 422);
        }
    }

    public function importarQuartos()
    {
        try {
            $this->servicoImportacao->importarQuartos();
            return response()->json(['message' => 'Quartos importados com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao importar quartos. Verifique se os hotéis foram importados antes: ' . $e->getMessage()], 422);
        }
    }

    public function importarTarifas()
    {
        try {
            $this->servicoImportacao->importarTarifas();
            return response()->json(['message' => 'Tarifas importadas com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao importar tarifas. Verifique se os hotéis foram importados antes: ' . $e->getMessage()], 422);
        }
    }

    public function importarReservas()
    {
        try {
            $this->servicoImportacao->importarReservas();
            return response()->json(['message' => 'Reservas importadas com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao importar reservas. Verifique se hotéis, quartos e tarifas foram importados antes: ' . $e->getMessage()], 422);
        }
    }
}
