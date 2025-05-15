<?php

use Illuminate\Support\Facades\Route;

Route::get('/menu-editor', function () {
    return view('menu-editor::page');
})->middleware(['web', 'auth'])->name('menu-editor');
