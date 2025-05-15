<?php

use Illuminate\Support\Facades\Route;

Route::get('/menu-editor', function () {
    return view('menu-editor::page');
})->name('menu-editor');
