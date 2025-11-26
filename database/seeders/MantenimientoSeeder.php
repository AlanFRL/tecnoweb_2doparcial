<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mantenimiento;
use Carbon\Carbon;

class MantenimientoSeeder extends Seeder
{
    public function run(): void
    {
        $mantenimientos = [
            [
                'equipo_codigo' => 'LAV-001',
                'fecha' => Carbon::parse('2024-08-15'),
                'descripcion' => 'Mantenimiento preventivo: limpieza de filtros, revisión de correas y ajuste de sistema de drenaje',
                'costo' => 250.00
            ],
            [
                'equipo_codigo' => 'LAV-002',
                'fecha' => Carbon::parse('2024-09-10'),
                'descripcion' => 'Reparación de bomba de agua y cambio de mangueras',
                'costo' => 450.00
            ],
            [
                'equipo_codigo' => 'SEC-001',
                'fecha' => Carbon::parse('2024-07-20'),
                'descripcion' => 'Cambio de resistencia térmica y limpieza profunda del tambor',
                'costo' => 380.00
            ],
            [
                'equipo_codigo' => 'SEC-002',
                'fecha' => Carbon::parse('2024-10-05'),
                'descripcion' => 'Mantenimiento preventivo: limpieza de ductos y revisión de sensores',
                'costo' => 180.00
            ],
            [
                'equipo_codigo' => 'PLA-001',
                'fecha' => Carbon::parse('2024-08-30'),
                'descripcion' => 'Calibración de temperatura y cambio de correa de transmisión',
                'costo' => 320.00
            ],
            [
                'equipo_codigo' => 'LAV-003',
                'fecha' => Carbon::parse('2024-09-25'),
                'descripcion' => 'Reparación de tablero electrónico y reemplazo de válvula de entrada',
                'costo' => 520.00
            ],
            [
                'equipo_codigo' => 'PLA-002',
                'fecha' => Carbon::parse('2024-10-15'),
                'descripcion' => 'Mantenimiento preventivo: limpieza de placa, revisión de sistema de vapor',
                'costo' => 150.00
            ],
            [
                'equipo_codigo' => 'LAV-001',
                'fecha' => Carbon::parse('2024-11-10'),
                'descripcion' => 'Cambio de rodamientos del tambor y ajuste de amortiguadores',
                'costo' => 420.00
            ]
        ];

        foreach ($mantenimientos as $mantenimiento) {
            Mantenimiento::create($mantenimiento);
        }
    }
}
