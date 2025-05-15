@props([
    'title' => '',
    'icon' => null,
    'collapsible' => false,
    'headerAction' => null,
])

@php
    $cardId = 'card-' . \Illuminate\Support\Str::uuid();
@endphp

<div class="rounded-lg shadow {{ $attributes->get('class') }}"
    x-data="{ open: true }"
>
    {{-- Encabezado --}}
    <div class="flex items-center justify-between border border-gray-200 dark:border-gray-700 rounded-md py-2 px-4"
        :class="{ 'rounded-b-none': open }"
    >
        <div class="flex items-center gap-2">
            @if ($icon)
                <i class="{{ $icon }} text-blue-500"></i>
            @endif
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">{{ $title }}</h2>
        </div>

        @if ($headerAction)
        <div>
            {!! $headerAction !!}
        </div>
        @endif

        @if ($collapsible)
            <button
                @click="open = !open"
                class="text-gray-500 hover:text-gray-700 dark:hover:text-white focus:outline-none transition-transform duration-200"
                :class="{ 'rotate-180': !open }"
            >
                <i class="fas fa-chevron-down"></i>
            </button>
        @endif
    </div>

    {{-- Contenido --}}
    <div
        x-show="open"
        x-transition.duration.200ms
        class="border border-t-0 border-gray-200 dark:border-gray-700 rounded-b-lg px-4 pb-4 pt-3"
    >
        {{ $slot }}
    </div>
</div>