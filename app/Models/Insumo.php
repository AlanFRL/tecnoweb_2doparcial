<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Inventario;
use App\Models\ProcesoInsumo;

class Insumo extends Model
{
    protected $table = 'insumo';

    // PK = codigo (string)
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'cantidad',
        'unidad_medida',
        'stock',
        'stock_min',
        'stock_max',
        'costo_promedio',
        'estado',
    ];

    protected $casts = [
        'stock'          => 'float',
        'stock_min'      => 'float',
        'stock_max'      => 'float',
        'costo_promedio' => 'float',
        'estado'         => 'boolean',
    ];

    // Relación con Inventario (movimientos)
    public function movimientos(): HasMany
    {
        return $this->hasMany(Inventario::class, 'insumo_codigo', 'codigo');
    }

    // Relación con ProcesoInsumo
    public function procesosInsumo(): HasMany
    {
        return $this->hasMany(ProcesoInsumo::class, 'insumo_codigo', 'codigo');
    }
}
