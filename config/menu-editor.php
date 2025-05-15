<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    | You can add your own items to this array.
    |
    */

    'menu' => [

        ['header' => 'Principal'],
        [
            'text'  => 'Inicio',
            'route' => 'dashboard',
            'match' => 'dashboard',
            'icon'  => 'home',
        ],
        [
            'text' => 'Editor de menú',
            'route' => 'admin.menu-editor',
            'match' => 'admin.menu-editor',
            'icon' => 'list-bullet',
            'can' => 'admin.menu-editor',
        ],
        [
            'text'    => 'SubMenu',
            'icon'    => 'queue-list',
            'submenu' => [
                [
                    'text'   => 'Item1',
                    'route'  => 'dashboard',
                    'match'  => 'dashboard',
                    'icon'   => 'adjustments-vertical',
                ],
                [
                    'text'   => 'Item2',
                    'route'  => 'dashboard',
                    'match'  => 'dashboard',
                    'icon'   => 'queue-list',
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    | You can add your own items to this array.
    |
    */

    'admin' => [
        [
            'text'  => 'Dashboard',
            'route' => 'dashboard',
            'match' => 'dashboard',
            'icon'  => 'home',
        ],
    ],

];