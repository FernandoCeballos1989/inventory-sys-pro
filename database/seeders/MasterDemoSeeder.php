<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Provider;
use App\Models\Client;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Hash;

class MasterDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. USUARIO PARA EL RECLUTADOR
        User::create([
            'name' => 'Reclutador Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin123'),
        ]);

        // 2. CATEGORÍAS (Usando tus nombres)
        $categories = ['Electronics', 'Software', 'Books', 'Office Supplies', 'Hardware'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }

        // 3. PROVEEDORES (Columnas: company_name, contact_name, phone_number, address)
        $prov1 = Provider::create([
            'company_name' => 'Logitech Official',
            'contact_name' => 'Ventas Logitech',
            'phone_number' => '1-800-555-0199',
            'email' => 'sales@logitech.com',
            'address' => 'Silicon Valley, CA'
        ]);

        $prov2 = Provider::create([
            'company_name' => 'Dell Technologies',
            'contact_name' => 'Soporte Dell',
            'phone_number' => '1-800-DELL-PRO',
            'email' => 'support@dell.com',
            'address' => 'Round Rock, Texas'
        ]);

        // 4. CLIENTES (Columnas: name, fiscal_code, email, phone, send_address)
        $cli1 = Client::create([
            'name' => 'Tech Solutions Inc.',
            'fiscal_code' => 'B12345678',
            'email' => 'contact@techsolutions.com',
            'phone' => '555-0101',
            'send_address' => '123 Innovation Drive'
        ]);

        // 5. PRODUCTOS (Columnas: sku, name, selling_price, current_stock, min_stock, category_id)
        // Creamos un par manuales para control y el resto con Factory
        $p1 = Product::create([
            'sku' => 'CPU-INT-001',
            'name' => 'Intel Core i9-14900K',
            'selling_price' => 589.99,
            'current_stock' => 0, // Los triggers o el seeder de Stock lo subirán
            'min_stock' => 5,
            'category_id' => 1, // Electronics
            'warehouse_location' => 'A-01'
        ]);

        $p2 = Product::create([
            'sku' => 'GPU-NV-001',
            'name' => 'NVIDIA RTX 4090 Founders',
            'selling_price' => 1699.00,
            'current_stock' => 0,
            'min_stock' => 2,
            'category_id' => 5, // Hardware
            'warehouse_location' => 'B-04'
        ]);

        // Si tienes Factory configurado con los nombres correctos:
        // Product::factory()->count(10)->create(['category_id' => 1]);

        // 6. MOVIMIENTOS DE STOCK (Columnas: product_id, provider_id, client_id, type, quantity, price, remarks)
        // Entrada para el Producto 1
        Stock::create([
            'product_id'  => $p1->id,
            'provider_id' => $prov1->id,
            'client_id'   => null,
            'type'        => 'in',
            'quantity'    => 20,
            'price'       => $p1->selling_price * 0.7,
            'remarks'     => 'Carga inicial por Seeder',
        ]);

        // Entrada para el Producto 2
        Stock::create([
            'product_id'  => $p2->id,
            'provider_id' => $prov2->id,
            'client_id'   => null,
            'type'        => 'in',
            'quantity'    => 5,
            'price'       => $p2->selling_price * 0.7,
            'remarks'     => 'Stock inicial hardware entusiasta',
        ]);

        // Salida (Venta) para el Producto 1
        Stock::create([
            'product_id'  => $p1->id,
            'provider_id' => null,
            'client_id'   => $cli1->id,
            'type'        => 'out', // Asegúrate si es 'out' o 'exit'
            'quantity'    => 2,
            'price'       => $p1->selling_price,
            'remarks'     => 'Venta de prueba a cliente corporativo',
        ]);
    }
}
