<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <- IMPORTANTE
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // Nombre real de la tabla en tu BD
    protected $table = 'usuario';

    // Clave primaria
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $rememberTokenName = '';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'password',
        'tipo_usuario',
        'estado',
    ];

    // Ocultar en JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'estado'   => 'boolean',
        ];
    }
    
}
