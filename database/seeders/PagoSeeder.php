<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Orden;
use Carbon\Carbon;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        // Orden BEL-000001 (ENTREGADA, CONTADO, 65 Bs) - Pago completo en efectivo
        Pago::create([
            'orden_nro' => 'BEL-000001',
            'fecha' => Carbon::parse('2024-08-29 14:30:00'),
            'monto' => 65.00,
            'metodo' => 'EFECTIVO',
            'referencia' => null
        ]);

        // Orden BEL-000002 (ENTREGADA, CREDITO, 100 Bs) - Pagos parciales
        Pago::create([
            'orden_nro' => 'BEL-000002',
            'fecha' => Carbon::parse('2024-09-07 10:15:00'),
            'monto' => 50.00,
            'metodo' => 'EFECTIVO',
            'referencia' => 'Primer pago - 50% del total'
        ]);

        Pago::create([
            'orden_nro' => 'BEL-000002',
            'fecha' => Carbon::parse('2024-09-22 16:45:00'),
            'monto' => 50.00,
            'metodo' => 'EFECTIVO',
            'referencia' => 'Segundo pago - Saldo completo'
        ]);

        // Orden BEL-000003 (ENTREGADA, CONTADO, 154 Bs) - Pago completo en efectivo
        Pago::create([
            'orden_nro' => 'BEL-000003',
            'fecha' => Carbon::parse('2024-09-13 11:20:00'),
            'monto' => 154.00,
            'metodo' => 'EFECTIVO',
            'referencia' => null
        ]);

        // Orden BEL-000004 (ENTREGADA, CONTADO, 25 Bs) - Pago con QR
        Pago::create([
            'orden_nro' => 'BEL-000004',
            'fecha' => Carbon::parse('2024-09-19 15:10:00'),
            'monto' => 25.00,
            'metodo' => 'QR',
            'referencia' => 'TRX-QR-20240919-001 - PagoFácil'
        ]);

        // Orden BEL-000005 (ENTREGADA, CONTADO, 115 Bs) - Pago con QR
        Pago::create([
            'orden_nro' => 'BEL-000005',
            'fecha' => Carbon::parse('2024-09-27 09:30:00'),
            'monto' => 115.00,
            'metodo' => 'QR',
            'referencia' => 'TRX-QR-20240927-002 - PagoFácil'
        ]);

        // Orden BEL-000007 (ENTREGADA, CREDITO, 80 Bs) - Pagos parciales
        Pago::create([
            'orden_nro' => 'BEL-000007',
            'fecha' => Carbon::parse('2024-10-10 13:00:00'),
            'monto' => 40.00,
            'metodo' => 'EFECTIVO',
            'referencia' => 'Anticipo 50%'
        ]);

        Pago::create([
            'orden_nro' => 'BEL-000007',
            'fecha' => Carbon::parse('2024-10-25 10:30:00'),
            'monto' => 40.00,
            'metodo' => 'QR',
            'referencia' => 'TRX-QR-20241025-003 - Saldo restante'
        ]);

        // Orden BEL-000008 (ENTREGADA, CONTADO, 91 Bs) - Pago completo
        Pago::create([
            'orden_nro' => 'BEL-000008',
            'fecha' => Carbon::parse('2024-10-16 12:45:00'),
            'monto' => 91.00,
            'metodo' => 'EFECTIVO',
            'referencia' => null
        ]);

        // Orden BEL-000009 (ENTREGADA, CONTADO, 70 Bs) - Pago con QR
        Pago::create([
            'orden_nro' => 'BEL-000009',
            'fecha' => Carbon::parse('2024-10-20 14:20:00'),
            'monto' => 70.00,
            'metodo' => 'QR',
            'referencia' => 'TRX-QR-20241020-004 - PagoFácil'
        ]);

        // Orden BEL-000010 (ENTREGADA, CONTADO, 40 Bs) - Pago completo
        Pago::create([
            'orden_nro' => 'BEL-000010',
            'fecha' => Carbon::parse('2024-10-23 11:00:00'),
            'monto' => 40.00,
            'metodo' => 'EFECTIVO',
            'referencia' => null
        ]);

        // Órdenes 11-17 (ENTREGADAS, CONTADO) - Pagos completos
        for ($i = 11; $i <= 17; $i++) {
            $orden = Orden::where('nro', sprintf('BEL-%06d', $i))->first();
            
            if ($orden) {
                $metodo = ($i % 3 == 0) ? 'QR' : 'EFECTIVO';
                $referencia = null;
                
                if ($metodo == 'QR') {
                    $referencia = sprintf('TRX-QR-2024%02d%02d-%03d - PagoFácil', 11, $i, $i + 10);
                }

                Pago::create([
                    'orden_nro' => $orden->nro,
                    'fecha' => $orden->fecha_entrega->addHours(2),
                    'monto' => $orden->total,
                    'metodo' => $metodo,
                    'referencia' => $referencia
                ]);
            }
        }

        // Orden BEL-000006 (LISTA, no entregada aún) - Sin pago todavía
        // Órdenes 18-20 (LISTA o PENDIENTE) - Sin pagos
    }
}
