<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('??#-###')), // Ejemplo: AB1-234
            'name' => $this->faker->words(2, true),
            'selling_price' => $this->faker->randomFloat(2, 10, 2000),
            'current_stock' => 0, // Siempre 0 para que los triggers gestionen el stock después
            'min_stock' => $this->faker->numberBetween(3, 10),
            'warehouse_location' => $this->faker->randomElement(['Pasillo A', 'Pasillo B', 'Zona C', 'Sótano']),
            'category_id' => Category::inRandomOrder()->first()->id, // <--- Selecciona una categoría existente al azar
        ];
    }
}
