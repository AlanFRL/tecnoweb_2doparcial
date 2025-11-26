<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Alan Romero',
                'direccion' => 'Av. Santos Dumont #234, 3er Anillo',
                'telefono' => '78588605'
            ],
            [
                'nombre' => 'María García',
                'direccion' => 'Calle Warnes #567, Equipetrol',
                'telefono' => '70123456'
            ],
            [
                'nombre' => 'Carlos Mendoza',
                'direccion' => 'Av. Banzer #890, 4to Anillo',
                'telefono' => '71234567'
            ],
            [
                'nombre' => 'Ana López',
                'direccion' => 'Calle Beni #123, Centro',
                'telefono' => '72345678'
            ],
            [
                'nombre' => 'Roberto Paz',
                'direccion' => 'Av. Cristóbal de Mendoza #456, Norte',
                'telefono' => '73456789'
            ],
            [
                'nombre' => 'Laura Sánchez',
                'direccion' => 'Calle Arenales #789, Plan 3000',
                'telefono' => '74567890'
            ],
            [
                'nombre' => 'Jorge Ramírez',
                'direccion' => 'Av. Alemana #321, Equipetrol Norte',
                'telefono' => '75678901'
            ],
            [
                'nombre' => 'Patricia Torres',
                'direccion' => 'Calle Sucre #654, Barrio Urbari',
                'telefono' => '76789012'
            ],
            [
                'nombre' => 'Diego Flores',
                'direccion' => 'Av. Roca y Coronado #987, 2do Anillo',
                'telefono' => '77890123'
            ],
            [
                'nombre' => 'Sofía Vargas',
                'direccion' => 'Calle Velasco #147, Villa 1ro de Mayo',
                'telefono' => '78901234'
            ]
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
