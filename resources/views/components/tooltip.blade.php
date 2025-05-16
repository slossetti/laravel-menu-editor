<div
    x-data="{ shown: false }"
    x-on:mouseenter="shown = true"
    x-on:mouseleave="shown = false"
    class="relative group"
>
    {{ $slot }}

    <div
        x-show="shown"
        x-transition
        x-cloak
        class="absolute z-10 px-2 py-1 text-xs text-white bg-black rounded shadow whitespace-nowrap"
        :class="'{{ $position ?? 'left-full top-1/2 -translate-y-1/2 ml-2' }}'"
    >
        {{ $label }}
    </div>
</div>
