<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/menu-editor', function () {
            return view('menu-editor::page');
        })->name('menu-editor');
    });

