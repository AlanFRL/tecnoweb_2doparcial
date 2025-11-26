<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProcesoInsumo;
use App\Models\OrdenProceso;

class ProcesoInsumoSeeder extends Seeder
{
    public function run(): void
    {
        $procesosLavado = OrdenProceso::where('etapa', 'LAVADO')
            ->where('estado', 'FINALIZADO')
            ->get();

        $procesosSecado = OrdenProceso::where('etapa', 'SECADO')
            ->where('estado', 'FINALIZADO')
            ->get();

        // Para cada proceso de LAVADO: usar detergente, suavizante, cloro
        foreach ($procesosLavado as $proceso) {
            // Detergente: 200gr por ciclo
            ProcesoInsumo::create([
                'proceso_id' => $proceso->id,
                'insumo_codigo' => 'INS-001',
                'cantidad' => 200 * $proceso->ciclos
            ]);

            // Suavizante: 50ml por ciclo
            ProcesoInsumo::create([
                'proceso_id' => $proceso->id,
                'insumo_codigo' => 'INS-002',
                'cantidad' => 50 * $proceso->ciclos
            ]);

            // Cloro: 30ml por ciclo
            ProcesoInsumo::create([
                'proceso_id' => $proceso->id,
                'insumo_codigo' => 'INS-003',
                'cantidad' => 30 * $proceso->ciclos
            ]);

            // Quitamanchas (opcional, solo en algunos): 100gr por ciclo
            if ($proceso->id % 3 == 0) {
                ProcesoInsumo::create([
                    'proceso_id' => $proceso->id,
                    'insumo_codigo' => 'INS-005',
                    'cantidad' => 100 * $proceso->ciclos
                ]);
            }
        }

        // Para cada proceso de SECADO: usar fragancia
        foreach ($procesosSecado as $proceso) {
            // Fragancia: 40ml por ciclo
            ProcesoInsumo::create([
                'proceso_id' => $proceso->id,
                'insumo_codigo' => 'INS-004',
                'cantidad' => 40 * $proceso->ciclos
            ]);
        }
    }
}
