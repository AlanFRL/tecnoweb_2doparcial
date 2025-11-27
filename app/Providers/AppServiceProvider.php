<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL; // <- AÑADIR
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔒 Forzar https en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Inertia::share('menu', function () {
            if (!Auth::check()) {
                return [];
            }

            $tipo = Auth::user()->tipo_usuario;

            // Obtener todos los menús según permisos
            $menus = DB::table('menu as m')
                ->join('permisomenu as pm', 'pm.id_menu', '=', 'm.id')
                ->select('m.*')
                ->where('pm.tipo_usuario', $tipo)
                ->orderBy('m.id')
                ->get();

            // 🔗 NORMALIZAR RUTAS DEL MENÚ
            $menus = $menus->map(function ($menu) {
                if (isset($menu->ruta) && $menu->ruta) {
                    // Si no es ya absoluta (http...), la hacemos absoluta con APP_URL
                    if (!str_starts_with($menu->ruta, 'http')) {
                        $menu->ruta = url($menu->ruta);
                    }
                }
                return $menu;
            });

            // Construir menú jerárquico (padre → hijos)
            $menuFinal = [];

            foreach ($menus as $menu) {
                if ($menu->id_padre === null) {
                    // Crear nodo padre
                    $menu->hijos = [];
                    $menuFinal[$menu->id] = $menu;
                }
            }

            // Asignar hijos
            foreach ($menus as $menu) {
                if ($menu->id_padre !== null && isset($menuFinal[$menu->id_padre])) {
                    $menuFinal[$menu->id_padre]->hijos[] = $menu;
                }
            }

            return array_values($menuFinal);
        });
    }
}
