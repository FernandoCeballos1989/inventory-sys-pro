<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'company_name' => 'Logitech Official', // Antes era 'name'
                'contact_name' => 'Ventas Logitech',
                'phone_number' => '1-800-555-0199',   // Antes era 'phone'
                'email' => 'sales@logitech.com',
                'address' => 'Silicon Valley, CA'
            ],
            [
                'company_name' => 'Dell Technologies',
                'contact_name' => 'Soporte Dell',
                'phone_number' => '1-800-DELL-PRO',
                'email' => 'support@dell.com',
                'address' => 'Round Rock, Texas'
            ],
            [
                'company_name' => 'Penguin Random House',
                'contact_name' => 'Pedidos Libros',
                'phone_number' => '212-782-9000',
                'email' => 'orders@penguin.com',
                'address' => 'New York, NY'
            ],
            [
                'company_name' => 'Office Depot Solutions',
                'contact_name' => 'Business Solutions',
                'phone_number' => '800-463-3768',
                'email' => 'business@officedepot.com',
                'address' => 'Boca Raton, FL'
            ],
            [
                'company_name' => 'Samsung Electronics',
                'contact_name' => 'Samsung Supply',
                'phone_number' => '1-800-SAMSUNG',
                'email' => 'supply@samsung.com',
                'address' => 'Seoul, South Korea'
            ],
        ];

        foreach ($providers as $provider) {
            Provider::create($provider);
        }
    }
}
