<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipo;
use Carbon\Carbon;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = [
            // Lavadoras
            [
                'codigo' => 'LAV-001',
                'nombre' => 'Lavadora Industrial LG 20kg',
                'tipo' => 'LAVADORA',
                'marca' => 'LG',
                'modelo' => 'WM-2000',
                'fecha_compra' => Carbon::parse('2023-03-15'),
                'capacidad_kg' => 20.00,
                'consumo_electrico_kw' => 2.50,
                'consumo_agua_litros' => 120.00,
                'estado' => 'LIBRE'
            ],
            [
                'codigo' => 'LAV-002',
                'nombre' => 'Lavadora Samsung 15kg',
                'tipo' => 'LAVADORA',
                'marca' => 'Samsung',
                'modelo' => 'WA15-T5260',
                'fecha_compra' => Carbon::parse('2023-06-20'),
                'capacidad_kg' => 15.00,
                'consumo_electrico_kw' => 2.00,
                'consumo_agua_litros' => 95.00,
                'estado' => 'LIBRE'
            ],
            [
                'codigo' => 'LAV-003',
                'nombre' => 'Lavadora Whirlpool 18kg',
                'tipo' => 'LAVADORA',
                'marca' => 'Whirlpool',
                'modelo' => 'WLF-1800',
                'fecha_compra' => Carbon::parse('2024-01-10'),
                'capacidad_kg' => 18.00,
                'consumo_electrico_kw' => 2.30,
                'consumo_agua_litros' => 110.00,
                'estado' => 'LIBRE'
            ],
            // Secadoras
            [
                'codigo' => 'SEC-001',
                'nombre' => 'Secadora Industrial Mabe 25kg',
                'tipo' => 'SECADORA',
                'marca' => 'Mabe',
                'modelo' => 'SMC-2500',
                'fecha_compra' => Carbon::parse('2023-03-15'),
                'capacidad_kg' => 25.00,
                'consumo_electrico_kw' => 4.50,
                'consumo_agua_litros' => 0.00,
                'estado' => 'LIBRE'
            ],
            [
                'codigo' => 'SEC-002',
                'nombre' => 'Secadora LG 20kg',
                'tipo' => 'SECADORA',
                'marca' => 'LG',
                'modelo' => 'DL-2000E',
                'fecha_compra' => Carbon::parse('2023-08-05'),
                'capacidad_kg' => 20.00,
                'consumo_electrico_kw' => 4.00,
                'consumo_agua_litros' => 0.00,
                'estado' => 'LIBRE'
            ],
            // Planchadoras
            [
                'codigo' => 'PLA-001',
                'nombre' => 'Plancha Industrial de Rodillo',
                'tipo' => 'PLANCHADORA',
                'marca' => 'Girbau',
                'modelo' => 'GP-320',
                'fecha_compra' => Carbon::parse('2023-05-12'),
                'capacidad_kg' => 10.00,
                'consumo_electrico_kw' => 3.00,
                'consumo_agua_litros' => 5.00,
                'estado' => 'LIBRE'
            ],
            [
                'codigo' => 'PLA-002',
                'nombre' => 'Plancha a Vapor Industrial',
                'tipo' => 'PLANCHADORA',
                'marca' => 'Electrolux',
                'modelo' => 'EI-1500',
                'fecha_compra' => Carbon::parse('2024-02-20'),
                'capacidad_kg' => 8.00,
                'consumo_electrico_kw' => 2.80,
                'consumo_agua_litros' => 8.00,
                'estado' => 'LIBRE'
            ]
        ];

        foreach ($equipos as $equipo) {
            Equipo::create($equipo);
        }
    }
}
