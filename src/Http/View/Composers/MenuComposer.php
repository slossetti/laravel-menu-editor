<?php

namespace MenuEditor\Http\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        // Tipo fijo por ahora
        $type = 'menu';

        // Modelo configurado en el paquete
        $model = config('menu-editor.menu_model');

        $cacheKey = "menu_{$type}";

        $menu = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($model, $type) {
            return $model::where('type', $type)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => fn ($q) => $q->orderBy('order')])
                ->get();
        });

        $view->with('menu', $menu);
    }
}
