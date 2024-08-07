<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;

class Asignar extends Component
{

    use WithPagination;

    public $role, $componentName, $permisosSelected = [], $old_permissions = [];
    private $pagination = 10;

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
    public function mount()
    {
        $this->role = 'Elegir';
        $this->componentName = 'Asignar Permisos';
    }

    public function render()
    {
        $permisos = Permission::select('name', 'id', DB::raw("0 as checked"))
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);

        $rolePermissionsCount = [];

        if ($this->role != 'Elegir') {
            $role = Role::find($this->role);

            if ($role) {
                $assignedPermissions = $role->permissions()->pluck('id')->toArray();

                foreach ($permisos as $permiso) {
                    if (in_array($permiso->id, $assignedPermissions)) {
                        $permiso->checked = 1;
                    }
                }

                $this->old_permissions = $assignedPermissions;

                // Contar los permisos del rol seleccionado
                $rolePermissionsCount[$this->role] = $role->permissions()->count();
            }
        }

        // Contar los permisos para todos los roles
        $allRoles = Role::orderBy('name', 'asc')->get();
        foreach ($allRoles as $role) {
            $rolePermissionsCount[$role->id] = $role->permissions()->count();
        }


        return view('livewire.asignar.components',[
            'roles' =>  Role::orderBy('name','asc')->get(),
            'permisos' => $permisos,
            'rolePermissionsCount' => $rolePermissionsCount,
        ])->extends('layouts.app')
        ->section('content');
    }

    public function Removeall()
    {
        if($this->role == 'Elegir')
        {
            $this->dispatch('showNotification', 'Elegir un Rol válido', 'warning');
            return;
        }

        $role = Role::find($this->role);
        $role->syncPermissions([]);

        //$this->dispatch('removeall',"Se revocaron todos los permisos al role $role->name ");
        $this->dispatch('showNotification', 'Se revocaron todos los permisos al role', 'error');
    }

    public function SyncAll()
    {
        if($this->role == 'Elegir')
        {
            $this->dispatch('showNotification', 'Elegir un Rol válido', 'warning');
            return;
        }

        $this->dispatch('confirmSyncAll', type: 'Sincronizar', name: 'PERMISOS');

    }

    public function performSync()
    {
        if($this->role == 'Elegir')
        {
            $this->dispatch('showNotification', 'No se realizo la sincronización', 'dark');
            return;
        }

        $role = Role::find($this->role);
        $permisos = Permission::pluck('id')->toArray();
        $role->syncPermissions($permisos);
        $this->dispatch('showNotification', 'Se sincronizaron todos los permisos al Role', 'success');

    }

    public function syncPermiso($state, $permisoName)
    {
        if ($this->role == 'Elegir') {
            $this->dispatch('showNotification', 'Elige un Rol válido', 'warning');
            return;
        }

        $role = Role::find($this->role);
        if ($state) {
            $role->givePermissionTo($permisoName);
            $this->dispatch('showNotification', 'Permiso asignado correctamente', 'info');
        } else {
            $role->revokePermissionTo($permisoName);
            $this->dispatch('showNotification', 'Permiso eliminado correctamente', 'error');
        }
    }
    protected $listeners = [
        'syncAllConfirmed' => 'performSync',
    ];
}
