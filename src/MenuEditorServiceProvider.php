<?php

namespace MenuEditor;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MenuEditor\Livewire\MenuManager;
use MenuEditor\Livewire\Modals\MenuFormModal;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use MenuEditor\Http\View\Composers\MenuComposer;

class MenuEditorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'menu-editor');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        View::composer('*', MenuComposer::class);

        Blade::anonymousComponentNamespace('menu-editor::components', 'menu-editor');

        Livewire::component('menu-manager', MenuManager::class);
        Livewire::component('modals.menu-form-modal', MenuFormModal::class);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/menu-editor'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/menu-editor.php' => config_path('menu-editor.php'),
        ], 'config');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/menu-editor.php', 'menu-editor');
    }
}