<div class="p-6 space-y-6">
    {{-- <x-header title="{{ $title }}" /> --}}

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input label="Texto" wire:model="text" required />
        <x-input label="Ruta (opcional)" wire:model="route" />
        <x-input label="Match (opcional)" wire:model="match" />
        <x-input label="Ícono (opcional)" wire:model="icon" />
        <x-input label="Permiso (can)" wire:model="can" />

        <x-select
            label="Submenú de (opcional)"
            wire:model="parent_id"
            option-label="text"
            option-value="id"
            :options="$menuOptions"
            placeholder="— Ninguno —"
        />

        <div class="col-span-2 flex gap-2 justify-end">
            <x-button label="Cancelar" flat type="button" wire:click="$dispatch('closeModal')" icon="x-mark" />
            <x-button primary type="submit" icon="check">
                Guardar
            </x-button>
        </div>
    </form>
</div>
