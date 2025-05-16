@props(['menuItems' => null, 'type' => null])

@php
    use MenuEditor\Services\MenuService;

    // Si no vino $menuItems, lo busco con el type que me pasaron
    if (! is_null($type)) {
        $menuItems = MenuService::getMenuItems($type);
    }
@endphp

<nav class="w-full px-3" x-data="{ sidebarOpen: true }">
    <ul class="space-y-1">
        @foreach ($menuItems as $item)
            @php
                $hasChildren = $item->children && $item->children->isNotEmpty();
                $hasRoute = filled($item->route) && Route::has($item->route);
                $isActive = filled($item->match) && request()->routeIs($item->match);
            @endphp

            {{-- Encabezado simple --}}
            @if (is_null($item->route) && is_null($item->parent_id) && $item->children->isEmpty())
                <li x-show="sidebarOpen" x-cloak class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide dark:text-gray-400">
                    {{ $item->text }}
                </li>
                @continue
            @endif

            {{-- Ítem con submenú --}}
            @if ($hasChildren)
                @php
                    $expanded = $item->children->contains(fn ($sub) => filled($sub->match) && request()->routeIs($sub->match));
                @endphp

                <li x-data="{ open: {{ $expanded ? 'true' : 'false' }} }">
                    <button
                        @click="open = !open"
                        class="sidebar-item sidebar-parent-btn"
                        :class="sidebarOpen ? 'px-4 justify-between' : 'px-4 justify-center'"
                    >
                        <span class="sidebar-label">
                            <x-icon
                                name="chevron-right"
                                class="sidebar-icon"
                                x-show="sidebarOpen"
                                x-bind:class="{ 'rotate-90': open }"
                            />
                            <span x-show="sidebarOpen" x-cloak>{{ $item->text }}</span>
                        </span>
                    </button>

                    {{-- Submenú colapsado --}}
                    <ul x-show="!sidebarOpen" x-cloak class="sidebar-submenu-collapsed">
                        @foreach ($item->children as $sub)
                            @php
                                $subRoute = filled($sub->route) && Route::has($sub->route) ? route($sub->route) : '#';
                                $subActive = filled($sub->match) && request()->routeIs($sub->match);
                            @endphp

                            @if (!$sub->can || auth()->user()->can($sub->can))
                                <li class="flex justify-center">
                                    <a
                                        href="{{ $subRoute }}"
                                        class="sidebar-subitem flex items-center transition-all {{ $subActive ? 'sidebar-active' : 'sidebar-inactive' }}"
                                    >
                                        @if ($sub->icon)
                                            <x-menu-editor::tooltip :label="$sub->text">
                                                <x-icon :name="$sub->icon" class="w-5 h-5 text-zinc-500 dark:text-zinc-300" />
                                            </x-menu-editor::tooltip>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    {{-- Submenú expandido --}}
                    <ul x-show="sidebarOpen && open" x-cloak class="sidebar-submenu">
                        @foreach ($item->children as $sub)
                            @php
                                $subRoute = filled($sub->route) && Route::has($sub->route) ? route($sub->route) : '#';
                                $subActive = filled($sub->match) && request()->routeIs($sub->match);
                            @endphp

                            @if (!$sub->can || auth()->user()->can($sub->can))
                                <li>
                                    <a
                                        href="{{ $subRoute }}"
                                        class="sidebar-subitem flex items-center transition-all {{ $subActive ? 'sidebar-active' : 'sidebar-inactive' }}"
                                    >
                                        <span class="transition-all">{{ $sub->text }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

            {{-- Ítem simple con o sin ruta --}}
            @elseif (!$item->can || auth()->user()->can($item->can))
                <li>
                    <a
                        href="{{ $hasRoute ? route($item->route) : '#' }}"
                        class="sidebar-item flex items-center transition-all {{ $isActive ? 'sidebar-active' : 'sidebar-inactive' }}"
                        :class="sidebarOpen ? 'px-4 justify-start' : 'px-4 justify-center'"
                    >
                        <template x-if="!sidebarOpen">
                            <x-menu-editor::tooltip :label="$item->text">
                                <x-icon name="{{ $item->icon }}" class="w-5 h-5 shrink-0" />
                            </x-menu-editor::tooltip>
                        </template>

                        <template x-if="sidebarOpen">
                            <x-icon name="{{ $item->icon }}" class="w-5 h-5 shrink-0" />
                        </template>

                        <span x-show="sidebarOpen" x-cloak class="ml-2 transition-all">
                            {{ $item->text }}
                        </span>
                    </a>
                </li>

            {{-- Fallback por si no entra en ninguna condición --}}
            @else
                <li class="text-red-500 text-sm">Item no reconocido: {{ $item->text }}</li>
            @endif
        @endforeach
    </ul>
</nav>
