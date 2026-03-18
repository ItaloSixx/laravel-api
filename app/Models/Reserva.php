<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    public $incrementing = false;
    protected $fillable = [
        'id', 'hotel_id', 'room_id', 'roomreservation_id', 
        'customer_first_name', 'customer_last_name', 
        'arrival_date', 'departure_date', 'guest_count', 
        'guest_type', 'meal_plan', 'currency_code', 
        'total_price', 'date', 'time'
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function quarto(): BelongsTo
    {
        return $this->belongsTo(Quarto::class, 'room_id');
    }

    public function precos(): HasMany
    {
        return $this->hasMany(PrecoReserva::class, 'reservation_id');
    }
}
