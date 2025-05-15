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
    <x-menu-editor::flex-card
        title="Tipo de Menú"
        class="bg-gray-50 dark:bg-gray-800"
    >

        @if (!$creatingNewType)
            <div class="flex items-end gap-4">
                <div class="w-1/2">
                    <x-select label="Seleccionar tipo existente" wire:model.live="type">
                        <x-select.option value="menu" label="Principal" />
                        <x-select.option value="admin" label="Admin" />
                        <x-select.option value="aprobador" label="Aprobador" />
                    </x-select>
                </div>

                <x-button
                    wire:click="$set('creatingNewType', true)"
                    icon="plus"
                    label="Crear nuevo tipo"
                />
            </div>
        @else
            <form wire:submit.prevent="createNewType" class="flex items-end gap-4">
                <x-input label="Nombre del nuevo tipo" wire:model.defer="newType" placeholder="Ej: auditoria" />
                <x-button type="submit" icon="check" primary>Crear y usar</x-button>
                <x-button flat wire:click="$set('creatingNewType', false)" icon="x-mark">Cancelar</x-button>
            </form>
        @endif
    </x-menu-editor::flex-card>

    {{-- Lista de ítems actuales --}}
    <x-menu-editor::flex-card
        :title="'Ítems del menú (' . ucfirst($type) . ')'"
        class="bg-gray-50 dark:bg-gray-800"
            :headerAction="view('components.menu-create-button', ['type' => $type])->render()"
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
                                <x-icon name="bars-3" class="w-4 h-4 text-gray-500" />
                                {{ $menu->text }}
                            </div>
                            <div class="flex gap-2">
                                <x-button flat icon="pencil" wire:click="$dispatch('openModal', { component: 'admin.modals.menu-form-modal', arguments: { menuId: {{ $menu->id }}, type: '{{ $type }}' }})" />
                                <x-button flat icon="trash" wire:click="confirmDelete({{ $menu->id }})" color="red" />
                            </div>
                        </div>

                        @if ($menu->children->isNotEmpty())
                            <ul wire:sortable-group.item-group="{{ $menu->id }}" class="ml-4 space-y-2">
                                @foreach ($menu->children as $child)
                                    <li wire:sortable-group.item="{{ $child->id }}" wire:key="menu-child-{{ $child->id }}" class="flex justify-between items-center p-2 border rounded bg-gray-50 dark:bg-gray-800">
                                        <div wire:sortable-group.handle class="cursor-move flex items-center gap-2">
                                            <x-icon name="bars-3" class="w-4 h-4 text-gray-400" />
                                            {{ $child->text }}
                                        </div>
                                        <div class="flex gap-2">
                                            <x-button xs flat icon="pencil" wire:click="$dispatch('openModal', { component: 'admin.modals.menu-form-modal', arguments: { menuId: {{ $menu->id }}, type: '{{ $type }}' }})" />
                                            <x-button xs flat icon="trash" wire:click="confirmDelete({{ $child->id }})" color="red" />
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