<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    public $incrementing = false; // IDs do XML
    protected $fillable = ['id', 'name'];

    public function quartos(): HasMany
    {
        return $this->hasMany(Quarto::class, 'hotel_id');
    }

    public function tarifas(): HasMany
    {
        return $this->hasMany(Tarifa::class, 'hotel_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'hotel_id');
    }
}
