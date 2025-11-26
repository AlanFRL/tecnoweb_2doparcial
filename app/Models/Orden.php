<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    protected $table = 'orden';
    protected $primaryKey = 'nro';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nro',
        'fecha_recepcion',
        'fecha_listo',
        'fecha_entrega',
        'estado',
        'forma_pago',
        'fecha_vencimiento',
        'subtotal',
        'descuento',
        'total',
        'cliente_id',
        'usuario_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_recepcion' => 'date',
        'fecha_listo' => 'date',
        'fecha_entrega' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relación con Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con Usuario (quien creó la orden)
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación con OrdenDetalle
    public function detalles(): HasMany
    {
        return $this->hasMany(OrdenDetalle::class, 'orden_nro', 'nro');
    }

    // Relación con OrdenProceso
    public function procesos(): HasMany
    {
        return $this->hasMany(OrdenProceso::class, 'orden_nro', 'nro');
    }

    // Relación con Pago
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'orden_nro', 'nro');
    }

    /**
     * Generar el siguiente número de orden
     */
    public static function generarNumeroOrden(): string
    {
        $ultimaOrden = self::orderBy('nro', 'desc')->first();
        
        if (!$ultimaOrden) {
            return 'BEL-000001';
        }

        $ultimoNumero = (int) substr($ultimaOrden->nro, 4);
        $nuevoNumero = $ultimoNumero + 1;
        
        return 'BEL-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular total pagado
     */
    public function totalPagado(): float
    {
        return (float) $this->pagos()->sum('monto');
    }

    /**
     * Calcular saldo pendiente
     */
    public function saldoPendiente(): float
    {
        return (float) $this->total - $this->totalPagado();
    }
}
