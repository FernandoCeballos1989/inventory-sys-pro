<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fiscal_code',
        'email',
        'phone',
        'send_address',
    ];

    /**
     * Relación: Un cliente puede recibir muchos movimientos de stock (salidas).
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
