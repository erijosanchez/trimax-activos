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
            ['name' => 'Parlantes', 'description' => 'Altavoces y equipos de sonido'],
            ['name' => 'Monitor', 'description' => 'Pantallas y displays'],
            ['name' => 'Tablet', 'description' => 'Tabletas electrónicas'],
            ['name' => 'Impresora', 'description' => 'Impresoras y escáneres'],
            ['name' => 'Proyector', 'description' => 'Proyectores y equipos de presentación'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
