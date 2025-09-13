<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
//use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Module;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Models\Permission;
use DB;

class Asignar extends Component
{

    use WithPagination;

    public $role, $componentName, $permisosSelected = [], $old_permissions = [], $moduleSelected = '';
    private $pagination = 10;

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
    public function mount()
    {
        $this->role = 'Elegir';
        $this->componentName = 'Asignar Permisos';
        //$this->ensureModulesExist();
    }
    public function updating($allRoles)
    {
        $this->resetPage();
    }

    private function ensureModulesExist()
    {
        // Módulos base que deberían existir
        $baseModules = [
            'users' => 'Usuarios',
            'roles' => 'Roles',
            'products' => 'Productos',
            'categories' => 'Categorias',
            'companies' => 'Compañías',
            'permissions' => 'Permisos',
            'denominations' => 'Monedas',
            'cashout' => 'Cierre Caja',
            'reports' => 'Reportes',
            'graphics' => 'Graficas y Estadistica',
            'assign' => 'Asignar',
            'pos' => 'Ventas',
            'api' => 'Factus',
            // Agrega más módulos según necesites
        ];

        foreach ($baseModules as $name => $description) {
            Module::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }

    public function render()
    {

        $query = Permission::select('permissions.*', DB::raw("0 as checked"));

        if ($this->moduleSelected !== 'all') {
            // Filtrar permisos por módulo seleccionado
            $module = Module::find($this->moduleSelected);
            if ($module) {
                $query->whereHas('modules', function ($q) use ($module) {
                    $q->where('modules.id', $module->id);
                });
            }
        }

        $permisos = $query->orderBy('name', 'asc')->paginate($this->pagination);

        // Cargar los roles disponibles
        $allRoles = Role::orderBy('name', 'asc')->get();

        if ($this->role != 'Elegir') {
            $role = Role::find($this->role);
            if ($role) {
                // Marcar permisos asignados al rol actual
                $assignedPermissions = $role->permissions()->pluck('id')->toArray();
                foreach ($permisos as $permiso) {
                    $permiso->checked = in_array($permiso->id, $assignedPermissions);
                }
            }
        }

        $modules = Module::where('active', true)->get();

        return view('livewire.asignar.components', [
            'roles' => $allRoles,
            'permisos' => $permisos,
            'modules' => $modules,
        ])->extends('layouts.app')->section('content');
    }

    public function Removeall()
    {
        if($this->role == 'Elegir')
        {
            //$this->dispatch('showNotification', 'Elegir un Rol válido', 'warning');
            $this->dispatch('noty-done', type:'warning', message: 'Selecciona un Rol válido');
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
            $this->dispatch('noty-done', type:'warning', message: 'Selecciona un Rol válido');
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
            //$this->dispatch('showNotification', 'Elige un Rol válido', 'warning');
            $this->dispatch('noty-done', type:'warning', message: 'Selecciona un Rol válido');
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

    public function syncAllFromModule()
    {
        if ($this->role == 'Elegir') {
            $this->dispatch('noty-done', type: 'warning', message: 'Selecciona un ROL válido');
            return;
        }

        if ($this->moduleSelected == 'all') {
            $this->dispatch('noty-done', type: 'warning', message: 'Selecciona un MODULO específico');
            return;
        }

        $role = Role::find($this->role);
        $module = Module::where('id', $this->moduleSelected)->first();

        if ($module) {
            $permissions = $module->permissions->pluck('id')->toArray();
            $role->syncPermissions($permissions);
            $this->dispatch('showNotification', 'Se asignaron todos los permisos del módulo seleccionado', 'success');
        } else {
            $this->dispatch('noty-done', type: 'error', message: 'No se encontró el módulo seleccionado');
        }
    }


    protected $listeners = [
        'syncAllConfirmed' => 'performSync',
    ];
}
