<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\MenuService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use WireUi\Traits\WireUiActions;

#[Title('Menu Manager')]
#[Layout('layouts.app')]
class MenuManager extends Component
{
    Use WireUiActions;

    public $listeners = [
        'menu-updated' => 'reloadMenuList',
        'menu-deleted' => 'reloadMenuList',
    ];

    public $type = 'menu';
    public $menus = [];
    public $types = [];

    public $text, $route, $match, $icon, $can, $parent_id;
    public $editingId = null;

    public $newType = '';
    public $creatingNewType = false;

    public function mount()
    {
        $this->loadTypes();
        $this->loadMenus();
    }

    public function updatedType()
    {
        $this->loadMenus();
        $this->resetInput();
    }

    public function loadTypes()
    {
        $this->types = Menu::select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->filter()
            ->values()
            ->toArray();
    }

    public function reloadMenuList()
    {
        MenuService::clearCache();
        $this->loadMenus();
    }


    public function createNewType()
    {
        $this->validate([
            'newType' => 'required|string|max:50|regex:/^[a-z0-9_\-]+$/i',
        ]);

        $this->type = $this->newType;
        $this->creatingNewType = false;
        $this->newType = '';
        $this->loadTypes();
        $this->loadMenus();
    }

    public function loadMenus()
    {
        $this->menus = Menu::where('type', $this->type)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => fn ($q) => $q->orderBy('order')])
            ->get();
    }

    public function resetInput()
    {
        $this->text = $this->route = $this->match = $this->icon = $this->can = null;
        $this->parent_id = null;
        $this->editingId = null;
    }

    public function confirmDelete($id): void
    {
        $this->notification()->confirm([
            'title' => 'Estas seguro?',
            'description' => 'Eliminar el item?',
            'acceptLabel' => 'Si, eliminar',
            'method' => 'delete',
            'params' =>  ['id' => $id],
        ]);
    }

    public function delete($id)
    {
        $this->notification()->send([
            'title' => 'Item eliminado',
            'description' => 'El item se eliminó correctamente.',
            'icon' => 'success',
        ]);

        Menu::where('id', $id)->orWhere('parent_id', $id)->delete();
        MenuService::clearCache();
        $this->loadMenus();

    }

    public function updateOrder($items)
    {
        foreach ($items as $index => $item) {
            Menu::where('id', $item['value'])->update([
                'order' => $index + 1,
            ]);
        }

        MenuService::clearCache();
        $this->loadMenus();
    }

    public function updateSubOrder($groups)
    {
        foreach ($groups as $group) {
            $parentId = (int) $group['value']; // ID del menú padre

            foreach ($group['items'] as $item) {
                Menu::where('id', $item['value'])->update([
                    'order' => $item['order'],
                    'parent_id' => $parentId,
                ]);
            }
        }

        MenuService::clearCache();
        $this->loadMenus();
    }


    public function render()
    {
        return view('livewire.admin.menu-manager');
    }
}