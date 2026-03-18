<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecoReserva extends Model
{
    use HasFactory;

    protected $table = 'reservation_prices';
    protected $fillable = ['reservation_id', 'rate_id', 'date', 'price'];

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'reservation_id');
    }

    public function tarifa(): BelongsTo
    {
        return $this->belongsTo(Tarifa::class, 'rate_id');
    }
}
