<div class="p-6 space-y-6">
    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Campo: Texto --}}
        <div>
            <label for="text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Texto <span class="text-red-500">*</span></label>
            <input type="text" id="text" wire:model="text" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Campo: Ruta --}}
        <div>
            <label for="route" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ruta (opcional)</label>
            <input type="text" id="route" wire:model="route"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Campo: Match --}}
        <div>
            <label for="match" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Match (opcional)</label>
            <input type="text" id="match" wire:model="match"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Campo: Ícono --}}
        <div>
            <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ícono (opcional)</label>
            <input type="text" id="icon" wire:model="icon"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Campo: Permiso (can) --}}
        <div>
            <label for="can" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Permiso (can)</label>
            <input type="text" id="can" wire:model="can"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Campo: Submenú de (select) --}}
        <div>
            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Submenú de (opcional)</label>
            <select id="parent_id" wire:model="parent_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                <option value="">— Ninguno —</option>
                @foreach ($menuOptions as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->text }}</option>
                @endforeach
            </select>
        </div>

        {{-- Botones --}}
        <div class="col-span-2 flex gap-2 justify-end mt-4">
            <button type="button" wire:click="$dispatch('closeModal')"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:hover:bg-gray-800">
                ✖ Cancelar
            </button>

            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                ✔ Guardar
            </button>
        </div>
    </form>
</div>
