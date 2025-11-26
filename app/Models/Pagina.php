<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    protected $table = 'pagina';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ruta',
        'visitas_totales',
    ];

    /**
     * Registrar una visita a esta página
     */
    public function registrarVisita(): int
    {
        $this->increment('visitas_totales');
        return $this->visitas_totales;
    }

    /**
     * Obtener o crear una página y registrar visita
     */
    public static function contarVisita(string $ruta, string $nombre): int
    {
        $pagina = self::firstOrCreate(
            ['ruta' => $ruta],
            ['nombre' => $nombre, 'visitas_totales' => 0]
        );

        return $pagina->registrarVisita();
    }
}
