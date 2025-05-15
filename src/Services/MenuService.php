<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class MenuService
{
    public static function clearCache(): void
    {
        // Evita error si se llama antes de migrar
        if (!Schema::hasTable('menus')) {
            return;
        }

        // Modelo configurable desde el paquete o la app
        $model = config('menu-editor.menu_model');

        if (!is_string($model) || !class_exists($model)) {
            throw new \RuntimeException("Modelo inválido en 'menu-editor.menu_model'");
        }

        // Obtener tipos únicos desde la base de datos
        $types = $model::select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->unique()
            ->values();

        foreach ($types as $type) {
            Cache::forget("menu_{$type}");
        }
    }
}