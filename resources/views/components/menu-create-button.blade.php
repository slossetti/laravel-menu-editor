<button
    type="button"
    wire:click="$dispatch('openModal', { component: 'modals.menu-form-modal', arguments: { type: '{{ $type }}' } })"
    class="inline-flex items-center gap-2 px-2 py-1.5 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 4v16m8-8H4" />
    </svg>
</button>
