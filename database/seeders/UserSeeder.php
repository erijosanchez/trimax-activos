<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        User::updateOrCreate(
            ['email' => 'admin@trimax.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Trimax2025!'),
                'role' => User::ROLE_ADMIN,
                'department' => 'Sistemas',
                'is_active' => true,
                'position' => 'Administrador del Sistema',
            ]
        );

        // Crear usuario de ejemplo para TI
        User::updateOrCreate(
            ['email' => 'ti@trimax.com'],
            [
                'name' => 'Soporte TI',
                'password' => Hash::make('Trimax2025!'),
                'role' => User::ROLE_TI,
                'department' => 'Tecnología',
                'is_active' => true,
                'position' => 'Técnico de Soporte',
            ]
        );

        // Crear usuario de ejemplo para Servicios Generales
        User::updateOrCreate(
            ['email' => 'servicios@trimax.com'],
            [
                'name' => 'Servicios Generales',
                'password' => Hash::make('Trimax2025!'),
                'role' => User::ROLE_SERVICIOS_GENERALES,
                'department' => 'Servicios Generales',
                'is_active' => true,
                'position' => 'Encargado de Servicios',
            ]
        );

        // Crear usuario de ejemplo para Marketing
        User::updateOrCreate(
            ['email' => 'marketing@trimax.com'],
            [
                'name' => 'Marketing',
                'password' => Hash::make('Trimax2025!'),
                'role' => User::ROLE_MARKETING,
                'department' => 'Marketing',
                'is_active' => true,
                'position' => 'Coordinador de Marketing',
            ]
        );
    }
}
