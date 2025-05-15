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
composer require slossetti/menu-editor
```

### Publicar recursos (opcional)

```bash
php artisan vendor:publish --tag=views
php artisan vendor:publish --tag=migrations
php artisan vendor:publish --tag=config
```

### Ejecutar migraciones

```bash
php artisan migrate
```

---

## 🧹 Uso

Incluí el componente en cualquier vista:

```blade
@livewire('menu-manager')
```

> El componente detecta el tipo de menú actual (`menu`, `admin`, `aprobador`) y permite gestionarlo de forma visual.

---

## Menu en vistas

Este paquete registra automáticamente un View Composer que inyecta los ítems del menú en todas las vistas bajo la variable:

```php
$menuItems
```

Esto te permite renderizar el menú desde cualquier lugar:

```blade
<ul>
    @foreach ($menuItems as $item)
        <li>
            <a href="{{ $item->route }}">
                {{ $item->text }}
            </a>
            @if ($item->children->isNotEmpty())
                <ul>
                    @foreach ($item->children as $child)
                        <li><a href="{{ $child->route }}">{{ $child->text }}</a></li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
```

Los ítems están cacheados automáticamente por tipo de menú (menu, admin, etc.).

## ⚙ Configuración

El archivo `config/menu-editor.php` se puede usar para definir opciones globales del editor (en desarrollo).

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
