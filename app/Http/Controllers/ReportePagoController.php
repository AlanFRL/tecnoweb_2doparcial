<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportePagoController extends Controller
{
    public function index()
    {
        // Total general cobrado
        $totalPagos = Pago::sum('monto');

        // Totales por método
        $totalEfvo = Pago::where('metodo', 'EFECTIVO')->sum('monto');
        $totalQr   = Pago::where('metodo', 'QR')->sum('monto');

        // Resumen por método (total y cantidad)
        $pagosPorMetodo = Pago::selectRaw('metodo, SUM(monto) as total, COUNT(*) as cantidad')
            ->groupBy('metodo')
            ->get();

        // Pagos por día (fecha, total del día, cantidad de pagos)
        $pagosPorDia = Pago::selectRaw('DATE(fecha) as fecha, SUM(monto) as total, COUNT(*) as cantidad')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Top órdenes por monto pagado (para ver cuáles más dinero generaron)
        $topOrdenes = Pago::selectRaw('orden_nro, SUM(monto) as total_pagado, COUNT(*) as cantidad_pagos')
            ->groupBy('orden_nro')
            ->orderByDesc('total_pagado')
            ->limit(10)
            ->get();

        return Inertia::render('Reportes/Pagos', [
            'totalPagos'     => $totalPagos,
            'totalEfvo'      => $totalEfvo,
            'totalQr'        => $totalQr,
            'pagosPorMetodo' => $pagosPorMetodo,
            'pagosPorDia'    => $pagosPorDia,
            'topOrdenes'     => $topOrdenes,
        ]);
    }
}
