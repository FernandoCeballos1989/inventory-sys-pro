<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('Pa$$w0rd!'), // Cambia esto por una contraseña segura
        ]);
        $this->call([
            CategorySeeder::class,  // 1. Primero las categorías
            ProviderSeeder::class,  // 2. Proveedores (necesarios para el stock)
            ClientSeeder::class,    // 3. Clientes (necesarios para futuras salidas)
            ProductSeeder::class,   // 4. Productos (necesitan category_id)
            StockSeeder::class,     // 5. Finalmente el Stock (necesita product, provider y client)
        ]);
    }
}
