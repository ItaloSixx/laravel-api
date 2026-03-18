<?php

namespace App\Http\Controllers;

use App\Services\ServicoImportacaoXml;
use RuntimeException;

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
        } catch (RuntimeException $e) {
            return response()->json(['message' => 'Erro ao importar hotéis: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno ao importar hotéis.'], 500);
        }
    }

    public function importarQuartos()
    {
        try {
            $this->servicoImportacao->importarQuartos();
            return response()->json(['message' => 'Quartos importados com sucesso!']);
        } catch (RuntimeException $e) {
            return response()->json(['message' => 'Erro ao importar quartos: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno ao importar quartos.'], 500);
        }
    }

    public function importarTarifas()
    {
        try {
            $this->servicoImportacao->importarTarifas();
            return response()->json(['message' => 'Tarifas importadas com sucesso!']);
        } catch (RuntimeException $e) {
            return response()->json(['message' => 'Erro ao importar tarifas: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno ao importar tarifas.'], 500);
        }
    }

    public function importarReservas()
    {
        try {
            $this->servicoImportacao->importarReservas();
            return response()->json(['message' => 'Reservas importadas com sucesso!']);
        } catch (RuntimeException $e) {
            return response()->json(['message' => 'Erro ao importar reservas: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno ao importar reservas.'], 500);
        }
    }
}
