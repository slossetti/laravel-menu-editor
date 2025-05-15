<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth']) // ajustable
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/menu-editor', \MenuEditor\Livewire\MenuManager::class)
            ->name('menu-editor');
    });
