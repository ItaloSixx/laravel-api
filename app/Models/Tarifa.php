<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarifa extends Model
{
    use HasFactory;

    protected $table = 'rates';
    public $incrementing = false;
    protected $fillable = ['id', 'hotel_id', 'name', 'active', 'price'];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function precosReserva(): HasMany
    {
        return $this->hasMany(PrecoReserva::class, 'rate_id');
    }
}
