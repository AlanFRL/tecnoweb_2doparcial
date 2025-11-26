<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Lavado Completo de Ropa',
                'descripcion' => 'Incluye lavado, secado, doblado y perfumado de ropa (poleras, pantalones, medias, ropa interior, toallas)',
                'tipo_cobro' => 'KILO',
                'precio_unitario' => 13.00,
                'estado' => true
            ],
            [
                'nombre' => 'Planchado',
                'descripcion' => 'Servicio de planchado por pieza de ropa',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 10.00,
                'estado' => true
            ],
            [
                'nombre' => 'Lavado de Zapatos (Par)',
                'descripcion' => 'Lavado profesional de calzado deportivo y casual',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 25.00,
                'estado' => true
            ],
            [
                'nombre' => 'Edredón 2 Plazas',
                'descripcion' => 'Lavado y secado de edredón de 2 plazas',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 70.00,
                'estado' => true
            ],
            [
                'nombre' => 'Colcha Delgada',
                'descripcion' => 'Lavado y secado de colcha delgada',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 50.00,
                'estado' => true
            ],
            [
                'nombre' => 'Colcha Grande',
                'descripcion' => 'Lavado y secado de colcha grande o pesada',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 80.00,
                'estado' => true
            ],
            [
                'nombre' => 'Juego de Sábanas',
                'descripcion' => 'Lavado completo de juego de sábanas (incluye sábana, funda de almohada)',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 35.00,
                'estado' => true
            ],
            [
                'nombre' => 'Alfombra Pequeña',
                'descripcion' => 'Lavado de alfombra hasta 2m²',
                'tipo_cobro' => 'PIEZA',
                'precio_unitario' => 40.00,
                'estado' => true
            ]
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
