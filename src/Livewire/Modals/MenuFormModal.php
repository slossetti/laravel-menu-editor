<?php

namespace MenuEditor\Livewire\Modals;

use MenuEditor\Services\MenuService;
use LivewireUI\Modal\ModalComponent;

class MenuFormModal extends ModalComponent
{
    public $menuModel;
    public $editingId;
    public $type;
    public $text, $route, $match, $icon, $can, $parent_id;
    public $menuOptions = [];

    public function mount($menuId = null, $type = 'menu')
    {
        $this->menuModel = config('menu-editor.menu_model');

        if (! class_exists($this->menuModel)) {
            throw new \RuntimeException("El modelo configurado en menu-editor.menu_model no existe.");
        }

        $this->type = $type;
        $this->menuOptions = $this->menuModel::where('type', $type)->whereNull('parent_id')->get();

        if ($menuId) {
            $item = $this->menuModel::findOrFail($menuId);
            $this->editingId = $item->id;
            $this->text = $item->text;
            $this->route = $item->route;
            $this->match = $item->match;
            $this->icon = $item->icon;
            $this->can = $item->can;
            $this->parent_id = $item->parent_id;

             // Sobreescribimos el type por si llega incorrecto
            $this->type = $item->type;
        }
    }

    public function save()
    {
        $data = $this->validate([
            'text' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'match' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'can' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
        ]);

        $data['type'] = $this->type;

        if (! $this->editingId) {
            $data['order'] = $this->menuModel::where('type', $this->type)
                ->where('parent_id', $this->parent_id)
                ->max('order') + 1;
        }

        $this->menuModel::updateOrCreate(['id' => $this->editingId], $data);

        MenuService::clearTypeCache($this->type);

        $this->dispatch('menu-updated'); // Notifica al padre si es necesario
        $this->closeModal();
    }

    public function render()
    {
        return view('menu-editor::modals.menu-form-modal');
    }
}
