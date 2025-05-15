<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class MenuService
{
    public static function clearCache(): void
    {
        foreach (['menu', 'admin', 'aprobador'] as $type) {
            Cache::forget("menu_{$type}");
        }
    }
}