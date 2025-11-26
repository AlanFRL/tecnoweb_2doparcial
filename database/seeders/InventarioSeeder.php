<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventario;
use Carbon\Carbon;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        $movimientos = [
            // INGRESOS (compras) - con proveedor y costo
            [
                'insumo_codigo' => 'INS-001',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-08-15'),
                'cantidad' => 5,  // 5 bolsas de 10kg
                'costo_unitario' => 180.00,
                'referencia' => 'Compra inicial - Factura #1234',
                'usuario_id' => 2,
                'proveedor_id' => 1
            ],
            [
                'insumo_codigo' => 'INS-002',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-08-15'),
                'cantidad' => 4,  // 4 botellas de 5L
                'costo_unitario' => 85.00,
                'referencia' => 'Compra inicial - Factura #1235',
                'usuario_id' => 2,
                'proveedor_id' => 2
            ],
            [
                'insumo_codigo' => 'INS-003',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-08-20'),
                'cantidad' => 5,  // 5 bidones de 5L
                'costo_unitario' => 35.00,
                'referencia' => 'Reposición - Factura #1240',
                'usuario_id' => 2,
                'proveedor_id' => 3
            ],
            [
                'insumo_codigo' => 'INS-004',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-09-10'),
                'cantidad' => 4,  // 4 botellas de 2L
                'costo_unitario' => 120.00,
                'referencia' => 'Compra fragancias - Factura #1250',
                'usuario_id' => 2,
                'proveedor_id' => 4
            ],
            [
                'insumo_codigo' => 'INS-005',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-09-15'),
                'cantidad' => 4,  // 4 botes de 3kg
                'costo_unitario' => 95.00,
                'referencia' => 'Compra quitamanchas - Factura #1255',
                'usuario_id' => 2,
                'proveedor_id' => 3
            ],

            // AJUSTES (devoluciones a proveedor) - con proveedor, con costo
            [
                'insumo_codigo' => 'INS-001',
                'tipo' => 'AJUSTE',
                'fecha' => Carbon::parse('2024-09-20'),
                'cantidad' => -1,  // Devolvemos 1 bolsa defectuosa
                'costo_unitario' => 180.00,
                'referencia' => 'Devolución por producto defectuoso - Nota de crédito #NC-001',
                'usuario_id' => 3,
                'proveedor_id' => 1
            ],
            [
                'insumo_codigo' => 'INS-002',
                'tipo' => 'AJUSTE',
                'fecha' => Carbon::parse('2024-10-05'),
                'cantidad' => -1,  // Devolvemos 1 botella con fuga
                'costo_unitario' => 85.00,
                'referencia' => 'Devolución por envase con fuga - Nota de crédito #NC-002',
                'usuario_id' => 3,
                'proveedor_id' => 2
            ],

            // BAJAS (pérdidas, vencimiento) - sin proveedor, sin costo
            [
                'insumo_codigo' => 'INS-003',
                'tipo' => 'BAJA',
                'fecha' => Carbon::parse('2024-10-10'),
                'cantidad' => -1,  // Baja por derrame
                'costo_unitario' => null,
                'referencia' => 'Baja por derrame accidental en almacén',
                'usuario_id' => 4,
                'proveedor_id' => null
            ],
            [
                'insumo_codigo' => 'INS-005',
                'tipo' => 'BAJA',
                'fecha' => Carbon::parse('2024-10-20'),
                'cantidad' => -1,  // Baja por vencimiento
                'costo_unitario' => null,
                'referencia' => 'Baja por fecha de vencimiento próxima',
                'usuario_id' => 4,
                'proveedor_id' => null
            ],

            // RETORNOS (reposición por proveedor tras ajuste) - con proveedor, sin costo
            [
                'insumo_codigo' => 'INS-001',
                'tipo' => 'RETORNO',
                'fecha' => Carbon::parse('2024-09-28'),
                'cantidad' => 1,  // Proveedor nos devuelve producto en buen estado
                'costo_unitario' => null,
                'referencia' => 'Retorno de proveedor por devolución anterior - Guía #RET-001',
                'usuario_id' => 2,
                'proveedor_id' => 1
            ],
            [
                'insumo_codigo' => 'INS-002',
                'tipo' => 'RETORNO',
                'fecha' => Carbon::parse('2024-10-12'),
                'cantidad' => 1,  // Reposición del producto devuelto
                'costo_unitario' => null,
                'referencia' => 'Reposición por producto defectuoso - Guía #RET-002',
                'usuario_id' => 2,
                'proveedor_id' => 2
            ],

            // Más INGRESOS recientes
            [
                'insumo_codigo' => 'INS-001',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-10-25'),
                'cantidad' => 3,  // 3 bolsas adicionales
                'costo_unitario' => 180.00,
                'referencia' => 'Reposición octubre - Factura #1280',
                'usuario_id' => 2,
                'proveedor_id' => 1
            ],
            [
                'insumo_codigo' => 'INS-004',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-11-01'),
                'cantidad' => 2,  // 2 botellas de fragancia
                'costo_unitario' => 120.00,
                'referencia' => 'Compra noviembre - Factura #1290',
                'usuario_id' => 2,
                'proveedor_id' => 4
            ],
            [
                'insumo_codigo' => 'INS-005',
                'tipo' => 'INGRESO',
                'fecha' => Carbon::parse('2024-11-10'),
                'cantidad' => 2,  // 2 botes de quitamanchas
                'costo_unitario' => 95.00,
                'referencia' => 'Reposición noviembre - Factura #1295',
                'usuario_id' => 2,
                'proveedor_id' => 3
            ]
        ];

        foreach ($movimientos as $movimiento) {
            Inventario::create($movimiento);
        }
    }
}
