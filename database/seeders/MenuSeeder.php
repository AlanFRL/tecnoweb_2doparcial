<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permisomenu')->truncate();
        DB::table('menu')->truncate();

        // ===============================
        // INSERTAR MENÚS SIN ID MANUAL
        // ===============================
        $menus = [
            ['nombre' => 'Dashboard',     'ruta' => '/dashboard',           'icono' => 'fa-solid fa-home',        'id_padre' => null],
            ['nombre' => 'Usuarios',      'ruta' => null,                    'icono' => 'fa-solid fa-users',       'id_padre' => null],

            // Submenús
            ['nombre' => 'Empleados',     'ruta' => '/usuarios/empleados',   'icono' => 'fa-solid fa-user-tie',    'id_padre' => 2],
            ['nombre' => 'Clientes',      'ruta' => '/usuarios/clientes',    'icono' => 'fa-solid fa-user',        'id_padre' => 2],

            ['nombre' => 'Órdenes',       'ruta' => '/ordenes',              'icono' => 'fa-solid fa-list',        'id_padre' => null],
            ['nombre' => 'Equipos',       'ruta' => '/equipos',              'icono' => 'fa-solid fa-gear',        'id_padre' => null],
            ['nombre' => 'Insumos',       'ruta' => '/insumos',              'icono' => 'fa-solid fa-box',         'id_padre' => null],
            ['nombre' => 'Promociones',   'ruta' => '/promociones',          'icono' => 'fa-solid fa-tags',        'id_padre' => null],
            ['nombre' => 'Pagos',         'ruta' => '/pagos',                'icono' => 'fa-solid fa-money-bill',  'id_padre' => null],
            ['nombre' => 'Reportes',      'ruta' => '/reportes',             'icono' => 'fa-solid fa-chart-bar',   'id_padre' => null],
            ['nombre' => 'Configuración', 'ruta' => '/configuracion',        'icono' => 'fa-solid fa-cog',         'id_padre' => null],
        ];

        // Insertar menú y obtener IDs reales
        foreach ($menus as $menu) {
            $id = DB::table('menu')->insertGetId([
                'nombre'   => $menu['nombre'],
                'ruta'     => $menu['ruta'],
                'icono'    => $menu['icono'],
                'id_padre' => $menu['id_padre'],
            ]);

            // Guardar para referencia por nombre
            $menuIds[$menu['nombre']] = $id;
        }

        // ===============================
        // INSERTAR PERMISOS usando nombres
        // ===============================
        $permisos = [
            // PROPIETARIO VE TODO
            ['tipo_usuario' => 'propietario', 'menu' => 'Dashboard'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Usuarios'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Empleados'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Clientes'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Órdenes'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Equipos'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Insumos'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Promociones'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Pagos'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Reportes'],
            ['tipo_usuario' => 'propietario', 'menu' => 'Configuración'],

            // EMPLEADO VE MENOS
            ['tipo_usuario' => 'empleado', 'menu' => 'Dashboard'],
            ['tipo_usuario' => 'empleado', 'menu' => 'Clientes'],
            ['tipo_usuario' => 'empleado', 'menu' => 'Órdenes'],
            ['tipo_usuario' => 'empleado', 'menu' => 'Insumos'],
            ['tipo_usuario' => 'empleado', 'menu' => 'Pagos'],
        ];

        foreach ($permisos as $permiso) {
            DB::table('permisomenu')->insert([
                'tipo_usuario' => $permiso['tipo_usuario'],
                'id_menu'      => $menuIds[$permiso['menu']],
            ]);
        }
    }
}
