<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promocion;
use App\Models\PromocionServicio;
use App\Models\Servicio;
use Carbon\Carbon;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        // Promoción 1: 2 Edredones por 100 Bs (descuento 28.57%)
        $promo1 = Promocion::create([
            'nombre' => 'Promo Edredones de Temporada',
            'descripcion' => '2 Edredones 2 plazas por solo 100 Bs (precio normal 140 Bs)',
            'descuento' => 28.57,
            'estado' => true
        ]);

        $servicioEdredon = Servicio::where('nombre', 'Edredón 2 Plazas')->first();
        PromocionServicio::create([
            'id_promocion' => $promo1->id,
            'id_servicio' => $servicioEdredon->id,
            'fecha_inicio' => Carbon::now()->subDays(15),
            'fecha_final' => Carbon::now()->addDays(45)
        ]);

        // Promoción 2: 2 Colchas Delgadas con descuento
        $promo2 = Promocion::create([
            'nombre' => 'Especial Colchas Delgadas',
            'descripcion' => '2 Colchas delgadas con 20% de descuento',
            'descuento' => 20.00,
            'estado' => true
        ]);

        $servicioColcha = Servicio::where('nombre', 'Colcha Delgada')->first();
        PromocionServicio::create([
            'id_promocion' => $promo2->id,
            'id_servicio' => $servicioColcha->id,
            'fecha_inicio' => Carbon::now()->subDays(10),
            'fecha_final' => Carbon::now()->addDays(50)
        ]);

        // Promoción 3: Lavado de Ropa en Fin de Semana
        $promo3 = Promocion::create([
            'nombre' => 'Descuento Fin de Semana',
            'descripcion' => '15% de descuento en lavado de ropa los fines de semana',
            'descuento' => 15.00,
            'estado' => true
        ]);

        $servicioLavado = Servicio::where('nombre', 'Lavado Completo de Ropa')->first();
        PromocionServicio::create([
            'id_promocion' => $promo3->id,
            'id_servicio' => $servicioLavado->id,
            'fecha_inicio' => Carbon::now()->subDays(30),
            'fecha_final' => Carbon::now()->addDays(30)
        ]);
    }
}
