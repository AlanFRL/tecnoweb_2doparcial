<?php

namespace App\Providers;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
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
