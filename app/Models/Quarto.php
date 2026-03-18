<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quarto extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    public $incrementing = false;
    protected $fillable = ['id', 'hotel_id', 'name', 'inventory_count'];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'room_id');
    }
}
