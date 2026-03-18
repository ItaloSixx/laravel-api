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
        $this->servicoImportacao->importarHoteis();
        return response()->json(['message' => 'Hotéis importados com sucesso!']);
    }

    public function importarQuartos()
    {
        $this->servicoImportacao->importarQuartos();
        return response()->json(['message' => 'Quartos importados com sucesso!']);
    }

    public function importarTarifas()
    {
        $this->servicoImportacao->importarTarifas();
        return response()->json(['message' => 'Tarifas importadas com sucesso!']);
    }

    public function importarReservas()
    {
        $this->servicoImportacao->importarReservas();
        return response()->json(['message' => 'Reservas importadas com sucesso!']);
    }
}
