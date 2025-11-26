<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'razon_social' => 'Distribuidora de Insumos Químicos S.R.L.',
                'telefono' => '33456789',
                'direccion' => 'Zona Industrial, Av. Cristo Redentor #2500, Santa Cruz'
            ],
            [
                'razon_social' => 'Detergentes y Suavizantes del Oriente',
                'telefono' => '33567890',
                'direccion' => 'Parque Industrial PI-CICAD, Santa Cruz'
            ],
            [
                'razon_social' => 'Proveeduría de Productos de Limpieza Ltda.',
                'telefono' => '33678901',
                'direccion' => 'Av. Tres Pasos al Frente #1234, Santa Cruz'
            ],
            [
                'razon_social' => 'Fragancias y Aromas Industriales Bolivia',
                'telefono' => '33789012',
                'direccion' => 'Calle Comercio #567, Zona Norte, Santa Cruz'
            ]
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
