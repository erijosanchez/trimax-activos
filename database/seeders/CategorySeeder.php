<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Celular', 'description' => 'Teléfonos móviles y smartphones'],
            ['name' => 'PC', 'description' => 'Computadoras de escritorio'],
            ['name' => 'Laptop', 'description' => 'Computadoras portátiles'],
            ['name' => 'Mouse', 'description' => 'Ratones y dispositivos señaladores'],
            ['name' => 'Teclado', 'description' => 'Teclados y accesorios de entrada'],
            ['name' => 'Audífonos', 'description' => 'Auriculares y headsets'],
            ['name' => 'Monitor', 'description' => 'Pantallas y displays'],
            ['name' => 'Tablet', 'description' => 'Tabletas electrónicas'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
