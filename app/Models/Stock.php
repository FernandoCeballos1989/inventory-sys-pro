<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'provider_id',
        'client_id',
        'type',
        'quantity',
        'price',
        'remarks',
    ];

    /**
     * Relación: El movimiento de stock pertenece a un producto.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación: El movimiento puede pertenecer a un proveedor (si es entrada 'in').
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Relación: El movimiento puede pertenecer a un cliente (si es salida 'out').
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
