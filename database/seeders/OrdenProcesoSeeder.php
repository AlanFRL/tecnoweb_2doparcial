<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrdenProceso;
use App\Models\Orden;
use App\Models\Equipo;

class OrdenProcesoSeeder extends Seeder
{
    public function run(): void
    {
        // Orden BEL-000001: Lavado de ropa 5kg (pasa por LAVADO, SECADO, PLANCHADO)
        $this->crearProcesosLavadoCompleto('BEL-000001');

        // Orden BEL-000002: 2 Edredones (solo LAVADO y SECADO)
        $this->crearProcesosEdredon('BEL-000002');

        // Orden BEL-000003: Lavado de ropa 8kg + Planchado
        $this->crearProcesosLavadoCompleto('BEL-000003');

        // Orden BEL-000004: Zapatos (solo LAVADO y SECADO)
        $this->crearProcesosZapatos('BEL-000004');

        // Orden BEL-000005: Colcha Grande + Juego de Sábanas
        $this->crearProcesosRopaCama('BEL-000005');

        // Orden BEL-000006: 3kg ropa
        $this->crearProcesosLavadoCompleto('BEL-000006');

        // Orden BEL-000007: 2 Colchas Delgadas
        $this->crearProcesosColchas('BEL-000007');

        // Orden BEL-000008: 7kg ropa
        $this->crearProcesosLavadoCompleto('BEL-000008');

        // Orden BEL-000009: Edredón
        $this->crearProcesosEdredon('BEL-000009');

        // Orden BEL-000010: Alfombra
        $this->crearProcesosAlfombra('BEL-000010');

        // Órdenes 11-17 (entregadas): Lavado completo
        for ($i = 11; $i <= 17; $i++) {
            $this->crearProcesosLavadoCompleto(sprintf('BEL-%06d', $i));
        }

        // Orden 18 (LISTA): Lavado y secado completo
        $this->crearProcesosLavadoCompleto('BEL-000018');

        // Órdenes 19-20 (PENDIENTE): Solo proceso LAVADO en curso
        $this->crearProcesosPendiente('BEL-000019');
        $this->crearProcesosPendiente('BEL-000020');
    }

    private function crearProcesosLavadoCompleto($nroOrden)
    {
        // LAVADO (30 min, LAV-001: 2.5kW, 120L agua por ciclo)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-001',
            'etapa' => 'LAVADO',
            'ciclos' => 1,
            'duracion_ciclo' => 30,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 2.5 * (30 / 60), // 1.25 kWh
            'agua_litros_consumidos' => 120.00
        ]);

        // SECADO (45 min, SEC-001: 4.5kW, 0L agua)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'SEC-001',
            'etapa' => 'SECADO',
            'ciclos' => 1,
            'duracion_ciclo' => 45,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 4.5 * (45 / 60), // 3.375 kWh
            'agua_litros_consumidos' => 0.00
        ]);

        // PLANCHADO (60 min, PLA-001: 3.0kW, 5L agua por ciclo)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'PLA-001',
            'etapa' => 'PLANCHADO',
            'ciclos' => 1,
            'duracion_ciclo' => 60,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 3.0 * (60 / 60), // 3.0 kWh
            'agua_litros_consumidos' => 5.00
        ]);
    }

    private function crearProcesosEdredon($nroOrden)
    {
        // LAVADO (ciclo más largo para edredones: 45 min, LAV-002: 2.0kW, 95L)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-002',
            'etapa' => 'LAVADO',
            'ciclos' => 2, // 2 ciclos para edredones grandes
            'duracion_ciclo' => 45,
            'estado' => 'FINALIZADO',
            'observacion' => 'Edredones requieren 2 ciclos',
            'kwh_consumidos' => 2.0 * (45 / 60) * 2, // 3.0 kWh
            'agua_litros_consumidos' => 95.00 * 2 // 190L
        ]);

        // SECADO (60 min, SEC-002: 4.0kW)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'SEC-002',
            'etapa' => 'SECADO',
            'ciclos' => 2,
            'duracion_ciclo' => 60,
            'estado' => 'FINALIZADO',
            'observacion' => 'Secado prolongado',
            'kwh_consumidos' => 4.0 * (60 / 60) * 2, // 8.0 kWh
            'agua_litros_consumidos' => 0.00
        ]);
    }

    private function crearProcesosZapatos($nroOrden)
    {
        // LAVADO (25 min, LAV-003: 2.3kW, 110L)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-003',
            'etapa' => 'LAVADO',
            'ciclos' => 1,
            'duracion_ciclo' => 25,
            'estado' => 'FINALIZADO',
            'observacion' => 'Programa especial para calzado',
            'kwh_consumidos' => 2.3 * (25 / 60), // 0.958 kWh
            'agua_litros_consumidos' => 110.00
        ]);

        // SECADO (40 min, SEC-001: 4.5kW)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'SEC-001',
            'etapa' => 'SECADO',
            'ciclos' => 1,
            'duracion_ciclo' => 40,
            'estado' => 'FINALIZADO',
            'observacion' => 'Secado suave',
            'kwh_consumidos' => 4.5 * (40 / 60), // 3.0 kWh
            'agua_litros_consumidos' => 0.00
        ]);
    }

    private function crearProcesosRopaCama($nroOrden)
    {
        // LAVADO (35 min, LAV-001: 2.5kW, 120L)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-001',
            'etapa' => 'LAVADO',
            'ciclos' => 1,
            'duracion_ciclo' => 35,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 2.5 * (35 / 60), // 1.458 kWh
            'agua_litros_consumidos' => 120.00
        ]);

        // SECADO (50 min, SEC-002: 4.0kW)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'SEC-002',
            'etapa' => 'SECADO',
            'ciclos' => 1,
            'duracion_ciclo' => 50,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 4.0 * (50 / 60), // 3.333 kWh
            'agua_litros_consumidos' => 0.00
        ]);
    }

    private function crearProcesosColchas($nroOrden)
    {
        // LAVADO (40 min, LAV-002: 2.0kW, 95L)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-002',
            'etapa' => 'LAVADO',
            'ciclos' => 2, // 2 colchas
            'duracion_ciclo' => 40,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 2.0 * (40 / 60) * 2, // 2.667 kWh
            'agua_litros_consumidos' => 95.00 * 2 // 190L
        ]);

        // SECADO (55 min, SEC-001: 4.5kW)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'SEC-001',
            'etapa' => 'SECADO',
            'ciclos' => 2,
            'duracion_ciclo' => 55,
            'estado' => 'FINALIZADO',
            'observacion' => null,
            'kwh_consumidos' => 4.5 * (55 / 60) * 2, // 8.25 kWh
            'agua_litros_consumidos' => 0.00
        ]);
    }

    private function crearProcesosAlfombra($nroOrden)
    {
        // LAVADO (50 min, LAV-003: 2.3kW, 110L)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-003',
            'etapa' => 'LAVADO',
            'ciclos' => 1,
            'duracion_ciclo' => 50,
            'estado' => 'FINALIZADO',
            'observacion' => 'Lavado profundo',
            'kwh_consumidos' => 2.3 * (50 / 60), // 1.917 kWh
            'agua_litros_consumidos' => 110.00
        ]);

        // SECADO (70 min, SEC-002: 4.0kW)
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'SEC-002',
            'etapa' => 'SECADO',
            'ciclos' => 1,
            'duracion_ciclo' => 70,
            'estado' => 'FINALIZADO',
            'observacion' => 'Secado completo',
            'kwh_consumidos' => 4.0 * (70 / 60), // 4.667 kWh
            'agua_litros_consumidos' => 0.00
        ]);
    }

    private function crearProcesosPendiente($nroOrden)
    {
        // Solo LAVADO en proceso
        OrdenProceso::create([
            'orden_nro' => $nroOrden,
            'equipo_codigo' => 'LAV-001',
            'etapa' => 'LAVADO',
            'ciclos' => 1,
            'duracion_ciclo' => 30,
            'estado' => 'EN PROCESO',
            'observacion' => 'En curso',
            'kwh_consumidos' => null, // Aún no finalizado
            'agua_litros_consumidos' => null
        ]);
    }
}
