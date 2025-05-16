<?php

namespace MenuEditor\Http\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use MenuEditor\Services\MenuService;

class MenuComposer
{
    public function compose(View $view)
    {
        $type = MenuService::resolveType(); // Detectar automáticamente

        $cacheKey = "menu_{$type}";

        $menu = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($type) {
            return MenuService::getMenuItems($type);
        });

        $view->with('menuItems', $menu);
    }
}
