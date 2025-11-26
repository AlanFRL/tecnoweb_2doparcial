<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromocionServicio extends Model
{
    protected $table = 'promocion_servicio';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_promocion',
        'id_servicio',
        'fecha_inicio',
        'fecha_final',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
    ];

    // Relación con Promocion
    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class, 'id_promocion');
    }

    // Relación con Servicio
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }
}
