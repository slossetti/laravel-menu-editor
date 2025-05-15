<?php

namespace MenuEditor\Livewire;

use Livewire\Component;
use App\Services\MenuService;

class MenuManager extends Component
{

    public $listeners = [
        'menu-updated' => 'reloadMenuList',
        'menu-deleted' => 'reloadMenuList',
    ];

    public $type = 'menu';
    public $menus = [];
    public $types = [];
    public $availableTypes = [];

    public $text, $route, $match, $icon, $can, $parent_id;
    public $editingId = null;

    public $newType = '';
    public $creatingNewType = false;

    public $menuModelClass;

    public function mount()
    {
        $this->menuModelClass = config('menu-editor.menu_model');

        if (!class_exists($this->menuModelClass)) {
            throw new \RuntimeException("El modelo [$this->menuModelClass] no existe. Verificá tu configuración.");
        }

        $this->loadTypes();

        // si no se eligió tipo y hay tipos disponibles, usar el primero
        if (!$this->type && count($this->types)) {
            $this->type = $this->types[0];
        }

        $this->loadMenus();
    }

    public function updatedType()
    {
        $this->loadMenus();
        $this->resetInput();
    }

    public function loadTypes()
    {
        $this->types = $this->menuModelClass::select('type')
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
        $this->menus = $this->menuModelClass::where('type', $this->type)
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

    public function delete($id)
    {

        $this->menuModelClass::where('id', $id)->orWhere('parent_id', $id)->delete();
        MenuService::clearCache();
        $this->loadMenus();
    }

    public function updateOrder($items)
    {
        foreach ($items as $index => $item) {
            $this->menuModelClass::where('id', $item['value'])->update([
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
                $this->menuModelClass::where('id', $item['value'])->update([
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
        return view('menu-editor::livewire.menu-manager');
    }
}