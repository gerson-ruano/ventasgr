<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Denomination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

class Coins extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $type, $value, $componentName, $pageTitle, $selected_id, $image, $imageUrl, $search;

    public $isModalOpen = false;
    private $pagination = 7;

    protected $rules = [
        'type' => 'required|not_in:Elegir',
        'value' => 'required|unique:denominations'
    ];

    protected $messages = [
        'type.required' => 'El tipo es requerido',
        'type.not_in' => 'Elige un valor para el tipo distinto a Elegir',
        'value.required' => 'El valor es requerido',
        'value.unique' => 'Ya existe el valor'
    ];

    public function mount()
    {
        $this->componentName = 'Denominaciones';
        $this->pageTitle = 'Listado';
        $this->type = null;
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function render()
    {
        $query = Denomination::orderBy('id', 'desc');

        if ($this->search) {
            $query->where('value', 'like', '%' . $this->search . '%');
        }

        $data = $query->paginate($this->pagination);

        return view('livewire.denominations.components', ['coins' => $data])
            ->extends('layouts.app')
            ->section('content');
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    #[On('noty-updated')]
    #[On('noty-added')]
    #[On('noty-deleted')]
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetUI();
        $this->resetValidation();
    }

    public function store()
    {
        try {
            // Validación de reglas
            $this->validate();
            $this->authorize('denominations.create', Denomination::class);

            $denomination = Denomination::create([
                'type' => $this->type,
                'value' => $this->value
            ]);

            if ($this->image) {
                $customFileName = uniqid() . '_.' . $this->image->extension();
                $this->image->storeAs('public/denominations', $customFileName);
                $denomination->image = $customFileName;
                $denomination->save();
            }

            $this->dispatch('noty-added', type: 'DENOMINACION', name: $denomination->type);
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            // Notificación de error de autorización
            $this->dispatch('noty-permission', type: 'USUARIO', name: 'PERMISOS', permission: 'CREAR');
        }
    }

    public function edit($id)
    {
        $record = Denomination::find($id, ['id', 'type', 'value', 'image']);
        $this->type = $record->type;
        $this->value = $record->value;
        $this->selected_id = $record->id;
        $this->imageUrl = $record->image ? Storage::url('denominations/' . $record->image) : null;
        $this->image = null;

        $this->openModal();
    }

    public function update()
    {
        try {
            // Actualización de reglas de validación para la edición
            $this->rules['value'] = "required|unique:denominations,value,{$this->selected_id}";

            // Validación
            $this->validate();
            $this->authorize('denominations.update', $this->selected_id);

            $denomination = Denomination::find($this->selected_id);
            $denomination->update([
                'type' => $this->type,
                'value' => $this->value
            ]);

            if ($this->image) {
                $customFileName = uniqid() . '_.' . $this->image->extension();
                $this->image->storeAs('public/denominations', $customFileName);

                $imageName = $denomination->image;
                $denomination->image = $customFileName;
                $denomination->save();

                if ($imageName != null) {
                    if (file_exists('storage/denominations' . $imageName)) {
                        unlink('storage/denominations' . $imageName);
                    }
                }
            }

            $this->dispatch('noty-updated', type: 'DENOMINACIÓN', name: $denomination->type);
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            // Notificación de error de autorización
            $this->dispatch('noty-permission', type: 'USUARIO', name: 'PERMISOS', permission: 'ACTUALIZAR');
        }
    }

    public function destroy($id)
    {
        try {
            $denomination = Denomination::find($id);
            $this->authorize('denominations.delete', $denomination);

            if ($denomination) {
                $imageName = $denomination->image;
                $denomination->delete();

                if ($imageName != null) {
                    if (file_exists(storage_path('app/public/denominations/' . $imageName))) {
                        unlink(storage_path('app/public/denominations/' . $imageName));
                    }
                }

                // Restablecer UI y emitir evento
                $this->dispatch('noty-deleted', type: 'DENOMINACIÓN', name: $denomination->type);
            } else {
                // Manejo de caso donde la categoría no se encuentra
                $this->dispatch('noty-not-found', type: 'DENOMINACIÓN', name: $denomination->id);
            }
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            $this->dispatch('noty-permission', type: 'USUARIO', name: 'PERMISOS', permission: 'ELIMINAR');
        }
    }


    protected $listeners = [
        'deleteRow' => 'destroy',
        'searchUpdated' => 'updateSearch',
    ];

    public function resetUI()
    {
        $this->type = 'Elegir';
        $this->value = '';
        $this->image = null;
        $this->imageUrl = null;
        //$this->search = '';
        $this->selected_id = 0;
    }

    public function updateSearch($search)
    {
        $this->search = $search;
    }
}
