<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Quarto;
use App\Models\Tarifa;
use App\Models\Reserva;
use App\Models\PrecoReserva;

class ServicoImportacaoXml
{
    /**
     * Importa os dados do arquivo hotels.xml
     */
    public function importarHoteis(): void
    {
        $caminhoXml = database_path('xml/hotels.xml');
        if (!file_exists($caminhoXml)) {
            throw new \Exception("Arquivo hotels.xml não encontrado.");
        }

        $xml = simplexml_load_file($caminhoXml);
        
        foreach ($xml->hotel as $hotelNode) {
            $id = (int) $hotelNode['id'];
            $name = (string) $hotelNode->name;

            Hotel::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }

    /**
     * Importa os dados do arquivo rooms.xml
     */
    public function importarQuartos(): void
    {
        $caminhoXml = database_path('xml/rooms.xml');
        if (!file_exists($caminhoXml)) {
            throw new \Exception("Arquivo rooms.xml não encontrado.");
        }

        $xml = simplexml_load_file($caminhoXml);
        
        foreach ($xml->room as $roomNode) {
            $id = (int) $roomNode['id'];
            $hotelId = (int) $roomNode['hotel_id'];
            $name = (string) $roomNode;
            $inventoryCount = (int) $roomNode['inventory_count'];

            Quarto::updateOrCreate(
                ['id' => $id],
                [
                    'hotel_id' => $hotelId,
                    'name' => $name,
                    'inventory_count' => $inventoryCount
                ]
            );
        }
    }

    /**
     * Importa os dados do arquivo rates.xml
     */
    public function importarTarifas(): void
    {
        $caminhoXml = database_path('xml/rates.xml');
        if (!file_exists($caminhoXml)) {
            throw new \Exception("Arquivo rates.xml não encontrado.");
        }

        $xml = simplexml_load_file($caminhoXml);
        
        foreach ($xml->rate as $rateNode) {
            $id = (int) $rateNode['id'];
            $hotelId = (int) $rateNode['hotel_id'];
            $active = (string) $rateNode['active'] === 'true' ? true : false;
            $name = (string) $rateNode;
            $price = (float) $rateNode['price'];

            Tarifa::updateOrCreate(
                ['id' => $id],
                [
                    'hotel_id' => $hotelId,
                    'name' => $name,
                    'active' => $active,
                    'price' => $price
                ]
            );
        }
    }

    /**
     * Importa os dados do arquivo reservations.xml
     */
    public function importarReservas(): void
    {
        $caminhoXml = database_path('xml/reservations.xml');
        if (!file_exists($caminhoXml)) {
            throw new \Exception("Arquivo reservations.xml não encontrado.");
        }

        $xml = simplexml_load_file($caminhoXml);
        
        foreach ($xml->reservation as $resNode) {
            $id = (int) $resNode->id;
            $hotelId = (int) $resNode->hotel_id;
            
            // Tratamento de nós aninhados
            $roomId = (int) $resNode->room->id;
            $customerFirstName = (string) $resNode->customer->first_name;
            $customerLastName = (string) $resNode->customer->last_name;
            $guestCount = (int) $resNode->guest_count['count'];
            $guestType = (string) $resNode->guest_count['type'];
            
            // Dados diretos
            $roomReservationId = (int) $resNode->roomreservation_id;
            $arrivalDate = (string) $resNode->arrival_date;
            $departureDate = (string) $resNode->departure_date;
            $mealPlan = isset($resNode->meal_plan) && (string) $resNode->meal_plan !== '' ? (string) $resNode->meal_plan : null;
            $currencyCode = (string) $resNode->currencycode;
            $totalPrice = (float) $resNode->totalprice;
            $date = (string) $resNode->date;
            $time = (string) $resNode->time;

            // Insere ou atualiza a reserva principal
            $reserva = Reserva::updateOrCreate(
                ['id' => $id],
                [
                    'hotel_id' => $hotelId,
                    'room_id' => $roomId,
                    'roomreservation_id' => $roomReservationId,
                    'customer_first_name' => $customerFirstName,
                    'customer_last_name' => $customerLastName,
                    'arrival_date' => $arrivalDate,
                    'departure_date' => $departureDate,
                    'guest_count' => $guestCount,
                    'guest_type' => $guestType,
                    'meal_plan' => $mealPlan,
                    'currency_code' => $currencyCode,
                    'total_price' => $totalPrice,
                    'date' => $date,
                    'time' => $time
                ]
            );

            // Garante idempotência para os preços: deleta os antigos e re-insere
            $reserva->precos()->delete();

            // Insere os novos preços/diárias
            foreach ($resNode->room->price as $priceNode) {
                $priceDate = (string) $priceNode['date'];
                $rateId = (int) $priceNode['rate_id'];
                $priceValue = (float) $priceNode;

                PrecoReserva::create([
                    'reservation_id' => $id,
                    'rate_id' => $rateId,
                    'date' => $priceDate,
                    'price' => $priceValue
                ]);
            }
        }
    }
}
