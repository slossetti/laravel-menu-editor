<?php

namespace MenuEditor;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MenuEditor\Livewire\MenuManager;
use MenuEditor\Livewire\Modals\MenuFormModal;

class MenuEditorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'menu-editor');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Livewire::component('menu-manager', MenuManager::class);
        Livewire::component('admin.modals.menu-form-modal', MenuFormModal::class);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/menu-editor'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/menu-editor.php', 'menu-editor');
    }
}