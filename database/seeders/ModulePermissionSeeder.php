<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ModulePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modulePermissions = [
            'users' => [
                'users.view',
                'users.create',
                'users.update',
                'users.delete'
            ],
            'roles' => [
                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete'
            ],
            'permissions' => [
                'permissions.view',
                'permissions.create',
                'permissions.update',
                'permissions.delete'
            ],
            'products' => [
                'products.view',
                'products.create',
                'products.update',
                'products.delete'
            ],
            'categories' => [
                'categories.view',
                'categories.create',
                'categories.update',
                'categories.delete'
            ],
            'companies' => [
                'companies.view',
                'companies.create',
                'companies.update',
                'companies.delete'
            ],
            'denominations' => [
                'denominations.view',
                'denominations.create',
                'denominations.update',
                'denominations.delete'
            ],
            'cashout' => [
                'cashout.view',
                'cashout.details',
                'cashout.pdf',
                'cashout.excel'
            ],
            'reports' => [
                'reports.view',
                'reports.update',
                'reports.details',
                'reports.pdf',
                'reports.excel'
            ],
            'graphics' => [
                'graphics.view',
                'graphics.details'
            ],
            'assign' => [
                'assign.view',
                'assing.update',
                'assing.delete',
                'assing.details'
            ],
            'pos' => [
                'pos.view',
                'pos.details'
            ],
            'api' => [
                'api.view',
                'api.create'
            ],
            // Agrega más módulos y sus permisos
        ];

        foreach ($modulePermissions as $moduleName => $permissions) {
            //$module = Module::where('name', $moduleName)->first();
            $module = Module::firstOrCreate(
                ['name' => $moduleName],
                ['description' => $this->translateModule($moduleName)]
            );
            if ($module) {
                foreach ($permissions as $permissionName) {
                    $permission = Permission::firstOrCreate(['name' => $permissionName]);
                    $module->assignPermission($permission);
                }
            }
        }
        // Crear o buscar el rol Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        // Asignar todos los permisos al rol Admin
        $adminRole->givePermissionTo(Permission::all());
    }
    public function translateModule($moduleName)
    {
        $traslation = [
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
        ];
        return $traslation[$moduleName] ?? $moduleName;
    }
}
