<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Software'],
            ['name' => 'Books'],
            ['name' => 'Office Supplies'],
            ['name' => 'Hardware'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
