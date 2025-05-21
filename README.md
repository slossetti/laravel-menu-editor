# Laravel Menu Editor

Editor visual de menús con soporte para submenús, ordenamiento drag & drop, y control de permisos. Construido con Laravel, Livewire 3 y Wire Elements Modal.

---

## 🚀 Características

- Crear, editar y eliminar ítems de menú
- Submenús anidados
- Reordenamiento con drag & drop (Livewire Sortable)
- Soporte para distintos tipos de menú (ej: `menu`, `admin`)
- Control de permisos (campo `can` por ítem)
- Modal de edición con Wire Elements
- Cache de menú con actualización dinámica

---

## 📆 Instalación

```bash
composer require slossetti/laravel-menu-editor
```

### Publicar recursos (opcional)

```bash
php artisan vendor:publish --tag=views
php artisan vendor:publish --tag=migrations
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=styles
```

### Ejecutar migraciones

```bash
php artisan migrate
```

---

## 🧹 Uso

Incluí el componente en cualquier vista:

```blade
<x-menu-editor::sidebar />
```

Si se tiene varios tipos de menu se puede especificar:

```blade
<x-menu-editor::sidebar type="Admin" />
```

> El componente detecta muestra el primer tipo de menu si no es especificado y permite gestionarlo de forma visual.

---

Los ítems están cacheados automáticamente por tipo de menú (menu, admin, etc.).

## ⚙ Configuración

El archivo `config/menu-editor.php` se puede usar para definir opciones globales del editor (en desarrollo).

```php
return [

    'menu_model' => MenuEditor\Models\Menu::class,
    'default_type' => 'menu',
];
```

### 🎨 Estilos del menú

Este paquete incluye una hoja de estilos con clases específicas para el sidebar y sus elementos (`sidebar-item`, `sidebar-active`, `sidebar-submenu`, etc.).

#### 🛠 Publicar el archivo CSS

Para usar los estilos por defecto, primero publicá el archivo:

```bash
php artisan vendor:publish --tag=styles
```

Esto copiará el archivo a:

```bash
resources/css/vendor/menu-editor.css
```

Luego, importalo en tu archivo principal (app.css):

```blade
@import 'vendor/menu-editor.css';
```

Y compilá los assets con:

```bash
npm run dev
# o
npm run build
```

---

## 🛠 Requisitos

- Laravel 10+
- PHP 8.1+
- Livewire ^3.0
- wire-elements/modal ^2.0
- livewire-sortable ^1.0.0
- Alpine.js (ya viene con Livewire 3)

Este paquete utiliza [`@livewire/sortable`](https://github.com/livewire/sortable) para permitir el ordenamiento drag & drop.  
Debés instalarlo vía NPM:

```bash
npm install livewire-sortable
```

Luego, agregá la importación en tu archivo `resources/js/app.js`:

```js
import 'livewire-sortable';
```

Y compilá tus assets:

```bash
npm run build
```

O para desarrollo:

```bash
npm run dev
```

---

## 📄 Licencia

MIT — Libre para uso y modificación.
