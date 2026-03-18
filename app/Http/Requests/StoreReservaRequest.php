<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|unique:reservations,id',
            'hotel_id' => 'required|integer|exists:hotels,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'roomreservation_id' => 'required|integer|unique:reservations,roomreservation_id',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'arrival_date' => 'required|date|before:departure_date',
            'departure_date' => 'required|date|after:arrival_date',
            'guest_count' => 'required|integer|min:1',
            'guest_type' => 'required|string',
            'meal_plan' => 'nullable|string',
            'currency_code' => 'required|string|size:3',
            'total_price' => 'required|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s'
        ];
    }
}
