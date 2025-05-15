<?php

namespace App\Livewire\Admin\Modals;

use App\Models\Menu;
use Livewire\Component;
use App\Services\MenuService;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\WireUiActions;

class MenuFormModal extends ModalComponent
{
    Use WireUiActions;

    public $editingId;
    public $type;
    public $text, $route, $match, $icon, $can, $parent_id;
    public $menuOptions = [];

    public function mount($menuId = null, $type = 'menu')
    {
        $this->type = $type;
        $this->menuOptions = Menu::where('type', $type)->whereNull('parent_id')->get();

        if ($menuId) {
            $item = Menu::findOrFail($menuId);
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
            $data['order'] = Menu::where('type', $this->type)
                ->where('parent_id', $this->parent_id)
                ->max('order') + 1;
        }

        Menu::updateOrCreate(['id' => $this->editingId], $data);

        MenuService::clearCache();

         $this->notification()->send([
                'title' => 'Item guardado',
                'description' => 'El item se guardó correctamente.',
                'icon' => 'success',
            ]);


        $this->dispatch('menu-updated'); // Notifica al padre si es necesario
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.modals.menu-form-modal');
    }
}
