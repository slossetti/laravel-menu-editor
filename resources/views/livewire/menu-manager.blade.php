<div class="space-y-6">

    <h1 class="text-2xl font-bold text-zinc-800 dark:text-white tracking-tight">
        Menu editor
    </h1>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
        Aquí puedes crear y editar los ítems del menú de la aplicación. Puedes crear submenús y asignar permisos a cada ítem.
        <br>
        <strong>Nota:</strong> Si cambias el tipo de menú, se eliminarán los ítems actuales. Asegúrate de hacer una copia de seguridad si es necesario.
    </p>

    {{-- Selección de tipo de menú --}}
    <x-menu-editor::flex-card title="Tipo de Menú" class="bg-gray-50 dark:bg-gray-800">
        @if (!$creatingNewType)
            <div class="flex items-end gap-4">
                <div class="w-1/2">
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccionar tipo existente</label>
                    <select
                        id="type"
                        wire:model.live="type"
                        class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    >
                        @foreach ($types as $typeOption)
                            <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <button
                    type="button"
                    wire:click="$set('creatingNewType', true)"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                    Crear nuevo tipo
                </button>
            </div>
        @else
            <form wire:submit.prevent="createNewType" class="flex items-end gap-4">
                <div class="w-1/2">
                    <label for="newType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del nuevo tipo</label>
                    <input id="newType" type="text" wire:model="newType" placeholder="Ej: auditoria" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" />
                </div>

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    Crear y usar
                </button>

                <button type="button" wire:click="$set('creatingNewType', false)" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded border hover:bg-gray-100 dark:border-gray-600 dark:text-white dark:hover:bg-gray-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancelar
                </button>
            </form>
        @endif
    </x-menu-editor::flex-card>

    {{-- Lista de ítems actuales --}}
    <x-menu-editor::flex-card
        :title="'Ítems del menú (' . ucfirst($type) . ')'"
        class="bg-gray-50 dark:bg-gray-800"
        :headerAction="view('menu-editor::components.menu-create-button', ['type' => $type])->render()"
    >
        @if ($menus->isEmpty())
            <div class="text-gray-500">No hay ítems en este menú.</div>
        @else
            <div
                wire:sortable="updateOrder"
                wire:sortable-group="updateSubOrder"
                wire:key="sortable-{{ $type }}"
                class="flex flex-col gap-4"
            >
                @foreach ($menus as $menu)
                    <div wire:sortable.item="{{ $menu->id }}" wire:key="menu-{{ $menu->id }}" class="border rounded-lg p-4 bg-white dark:bg-gray-900 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <div wire:sortable.handle class="cursor-move font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 10h16M4 14h16"></path></svg>
                                {{ $menu->text }}
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="$dispatch('openModal', { component: 'modals.menu-form-modal', arguments: { menuId: {{ $menu->id }}, type: '{{ $type }}' }})"
                                    class="p-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-white">
                                    ✏️
                                </button>
                                <button wire:click="delete({{ $menu->id }})"
                                    class="p-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-white">
                                    🗑️
                                </button>
                            </div>
                        </div>

                        @if ($menu->children->isNotEmpty())
                            <ul wire:sortable-group.item-group="{{ $menu->id }}" class="ml-4 space-y-2">
                                @foreach ($menu->children as $child)
                                    <li wire:sortable-group.item="{{ $child->id }}" wire:key="menu-child-{{ $child->id }}" class="flex justify-between items-center p-2 border rounded bg-gray-50 dark:bg-gray-800">
                                        <div wire:sortable-group.handle class="cursor-move flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 10h16M4 14h16"></path></svg>
                                            {{ $child->text }}
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="$dispatch('openModal', { component: 'modals.menu-form-modal', arguments: { menuId: {{ $menu->id }}, type: '{{ $type }}' }})"
                                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-white">
                                                ✏️
                                            </button>
                                            <button wire:click="delete({{ $child->id }})"
                                                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-white">
                                                🗑️
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </x-menu-editor::flex-card>

</div>