<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpia la tabla para evitar duplicados
        Menu::truncate();

        // Menús
        $this->seedGroup(config('menu.menu'), 'menu');
        $this->seedGroup(config('menu.admin'), 'admin');
        $this->seedGroup(config('menu.aprobador'), 'aprobador');

        \App\Services\MenuService::clearCache(); // Limpia caché luego de poblar
    }

    protected function seedGroup(array $items, string $type, ?int $parentId = null): void
    {
        foreach ($items as $index => $item) {
            // Si es un encabezado lo guardamos sin ruta e icono
            if (isset($item['header'])) {
                Menu::create([
                    'type'      => $type,
                    'parent_id' => $parentId,
                    'text'      => $item['header'],
                    'order'     => $index,
                ]);
                continue;
            }

            // Creamos el ítem padre
            $menu = Menu::create([
                'type'      => $type,
                'parent_id' => $parentId,
                'text'      => $item['text'],
                'route'     => $item['route'] ?? null,
                'match'     => $item['match'] ?? null,
                'icon'      => $item['icon'] ?? null,
                'can'       => $item['can'] ?? null,
                'order'     => $index,
            ]);

            // Si tiene submenú, lo recorremos recursivamente
            if (!empty($item['submenu'])) {
                $this->seedGroup($item['submenu'], $type, $menu->id);
            }
        }
    }
}
