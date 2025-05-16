<?php

namespace MenuEditor\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class MenuService
{
    public static function getMenuItems(?string $type = null)
    {
        $type ??= self::resolveType();

        if (!\Schema::hasTable('menus')) {
            return collect();
        }

        $model = config('menu-editor.menu_model');

        if (!is_string($model) || !class_exists($model)) {
            throw new \RuntimeException("Modelo inválido en 'menu-editor.menu_model'");
        }

        return $model::where('type', $type)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => fn ($q) => $q->orderBy('order')])
            ->get();
    }

    public static function resolveType(): string
    {
        return config('menu-editor.default_type', 'menu');
    }


    // public static function resolveType(): string
    // {
    //     $user = auth()->user();
    //     $isAdmin = request()->is('admin*');

    //     return match (true) {
    //         $user?->hasRole('aprobador') => 'aprobador',
    //         $isAdmin => 'admin',
    //         default => 'menu',
    //     };
    // }

}