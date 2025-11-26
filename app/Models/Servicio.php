<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servicio extends Model
{
    protected $table = 'servicio';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_cobro',
        'precio_unitario',
        'estado',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'estado' => 'boolean',
    ];

    // Relación con OrdenDetalle
    public function detallesOrden(): HasMany
    {
        return $this->hasMany(OrdenDetalle::class, 'servicio_id');
    }

    // Relación muchos a muchos con Promocion
    public function promociones(): BelongsToMany
    {
        return $this->belongsToMany(Promocion::class, 'promocion_servicio', 'id_servicio', 'id_promocion')
            ->withPivot('fecha_inicio', 'fecha_final');
    }

    /**
     * Obtener la promoción activa para este servicio (si existe)
     */
    public function promocionActiva()
    {
        $hoy = now()->toDateString();
        
        return $this->promociones()
            ->where('promocion.estado', true)
            ->whereRaw('? BETWEEN promocion_servicio.fecha_inicio AND promocion_servicio.fecha_final', [$hoy])
            ->orderBy('promocion.descuento', 'desc')
            ->first();
    }

    /**
     * Calcular descuento aplicable
     */
    public function calcularDescuento(float $subtotal): float
    {
        $promocion = $this->promocionActiva();
        
        if (!$promocion) {
            return 0.00;
        }

        return round($subtotal * ($promocion->descuento / 100), 2);
    }
}
