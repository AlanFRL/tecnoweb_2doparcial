<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventario';
    public $timestamps = false;

    protected $fillable = [
        'insumo_codigo',
        'tipo',
        'fecha',
        'cantidad',
        'costo_unitario',
        'referencia',
        'usuario_id',
        'proveedor_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'costo_unitario' => 'decimal:2',
    ];

    // Relación con Insumo
    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_codigo', 'codigo');
    }

    // Relación con Usuario (quien registró el movimiento)
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación con Proveedor
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
