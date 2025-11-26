<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insumo;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        $insumos = [
            [
                'codigo' => 'INS-001',
                'nombre' => 'Detergente Ariel Industrial 10kg',
                'cantidad' => 10000,
                'unidad_medida' => 'GR',
                'stock' => 45000.00,  // 4.5 bolsas
                'stock_min' => 20000.00,
                'stock_max' => 100000.00,
                'costo_promedio' => 180.00,
                'estado' => true
            ],
            [
                'codigo' => 'INS-002',
                'nombre' => 'Suavizante Downy Concentrado 5L',
                'cantidad' => 5000,
                'unidad_medida' => 'ML',
                'stock' => 18000.00,  // 3.6 botellas
                'stock_min' => 10000.00,
                'stock_max' => 50000.00,
                'costo_promedio' => 85.00,
                'estado' => true
            ],
            [
                'codigo' => 'INS-003',
                'nombre' => 'Cloro Desinfectante 5L',
                'cantidad' => 5000,
                'unidad_medida' => 'ML',
                'stock' => 22000.00,  // 4.4 bidones
                'stock_min' => 10000.00,
                'stock_max' => 40000.00,
                'costo_promedio' => 35.00,
                'estado' => true
            ],
            [
                'codigo' => 'INS-004',
                'nombre' => 'Fragancia Lavanda Industrial 2L',
                'cantidad' => 2000,
                'unidad_medida' => 'ML',
                'stock' => 7500.00,  // 3.75 botellas
                'stock_min' => 4000.00,
                'stock_max' => 20000.00,
                'costo_promedio' => 120.00,
                'estado' => true
            ],
            [
                'codigo' => 'INS-005',
                'nombre' => 'Quitamanchas Vanish 3kg',
                'cantidad' => 3000,
                'unidad_medida' => 'GR',
                'stock' => 12000.00,  // 4 botes
                'stock_min' => 6000.00,
                'stock_max' => 30000.00,
                'costo_promedio' => 95.00,
                'estado' => true
            ]
        ];

        foreach ($insumos as $insumo) {
            Insumo::create($insumo);
        }
    }
}
