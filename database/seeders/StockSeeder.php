<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtenemos todos los productos y proveedores existentes
        $products = Product::all();
        $providers = Provider::all();

        foreach ($products as $product) {
            // 2. Creamos un movimiento de entrada ('in') para cada producto
            Stock::create([
                'product_id'  => $product->id,
                'provider_id' => $providers->random()->id, // Proveedor al azar
                'client_id'   => null,                     // No hay cliente en una entrada
                'type'        => 'in',
                'quantity'    => rand(10, 100),            // Cantidad aleatoria entre 10 y 100
                'price'       => $product->selling_price * 0.6, // Precio de coste (60% del PVP)
                'remarks'     => 'Carga inicial automática',
            ]);
        }
    }
}
