@props(['menuItems'])

<nav class="w-full px-3">
    <ul class="space-y-1">
        @foreach ($menuItems as $item)

            {{-- Si es encabezado (sin ruta, sin hijos) --}}
            @if (is_null($item->route) && is_null($item->parent_id) && $item->children->isEmpty())
                <li x-show="sidebarOpen" x-cloak class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide dark:text-gray-400">
                    {{ $item->text }}
                </li>
                @continue
            @endif

            {{-- Si tiene submenú --}}
            @if ($item->children->isNotEmpty())
                @php
                    $expanded = $item->children->contains(fn ($sub) => request()->routeIs($sub->match));
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
                            @if (!$sub->can || auth()->user()->can($sub->can))
                                <li class="flex justify-center">
                                    <a
                                        href="{{ route($sub->route) }}"
                                        class="sidebar-subitem flex items-center transition-all
                                            {{ request()->routeIs($sub->match) ? 'sidebar-active' : 'sidebar-inactive' }}"
                                    >
                                        @if ($sub->icon)
                                            <x-tooltip :label="$sub->text">
                                                <x-icon :name="$sub->icon" class="w-5 h-5 text-zinc-500 dark:text-zinc-300" />
                                            </x-tooltip>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    {{-- Submenú expandido --}}
                    <ul x-show="sidebarOpen && open" x-cloak class="sidebar-submenu">
                        @foreach ($item->children as $sub)
                            @if (!$sub->can || auth()->user()->can($sub->can))
                                <li>
                                    <a
                                        href="{{ route($sub->route) }}"
                                        class="sidebar-subitem flex items-center transition-all
                                            {{ request()->routeIs($sub->match) ? 'sidebar-active' : 'sidebar-inactive' }}"
                                    >
                                        <span class="transition-all">{{ $sub->text }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

            {{-- Ítem simple --}}
            @elseif (!$item->can || auth()->user()->can($item->can))
                <li>
                    <a
                        href="{{ route($item->route) }}"
                        class="sidebar-item flex items-center transition-all
                            {{ request()->routeIs($item->match) ? 'sidebar-active' : 'sidebar-inactive' }}"
                        :class="sidebarOpen ? 'px-4 justify-start' : 'px-4 justify-center'"
                    >
                        <template x-if="!sidebarOpen">
                            <x-tooltip :label="$item->text">
                                <x-icon name="{{ $item->icon }}" class="w-5 h-5 shrink-0" />
                            </x-tooltip>
                        </template>

                        <template x-if="sidebarOpen">
                            <x-icon name="{{ $item->icon }}" class="w-5 h-5 shrink-0" />
                        </template>

                        <span x-show="sidebarOpen" x-cloak class="ml-2 transition-all">
                            {{ $item->text }}
                        </span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>