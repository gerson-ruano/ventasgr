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
                'users.delete',
            ],
            'roles' => [
                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete',
            ],
            'permissions' => [
                'permissions.view',
                'permissions.create',
                'permissions.update',
                'permissions.delete',
            ],
            'products' => [
                'products.view',
                'products.create',
                'products.update',
                'products.delete',
                'products.addstock',
            ],
            'categories' => [
                'categories.view',
                'categories.create',
                'categories.update',
                'categories.delete',
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
                'pos.details',
            ],
            'api' => [
                'api.view',
                'api.create',
            ],
        ];

        foreach ($modulePermissions as $moduleKey => $permissions) {

            // 🔹 módulo (usar key, NO name)
            $module = Module::firstOrCreate(
                ['key' => $moduleKey],
                [
                    'name' => ucfirst($moduleKey),
                    'description' => $this->translateModule($moduleKey),
                    'active' => true,
                ]
            );

            foreach ($permissions as $permissionName) {

                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);

                // 🔗 relacionar módulo ↔ permiso (pivot)
                $module->permissions()->syncWithoutDetaching($permission);
            }
        }

        // 🔐 ROLES
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        $employee = Role::firstOrCreate(['name' => 'Employee']);
        $employee->givePermissionTo(
            Permission::where('name', 'like', 'pos.%')->get()
        );
        $employee->givePermissionTo(
            Permission::where('name', 'like', 'products.%')->get()
        );

        $seller = Role::firstOrCreate(['name' => 'Seller']);
        $seller->givePermissionTo(
            Permission::where('name', 'like', 'pos.%')->get()
        );
    }

        protected function translateModule($key)
    {
        return [
            'users' => 'Usuarios',
            'roles' => 'Roles',
            'permissions' => 'Permisos',
            'products' => 'Productos',
            'categories' => 'Categorías',
            'companies' => 'Compañías',
            'permissions' => 'Permisos',
            'denominations' => 'Monedas',
            'cashout' => 'Cierre Caja',
            'reports' => 'Reportes',
            'graphics' => 'Graficas',
            'assign' => 'Asignar',
            'pos' => 'Ventas',
            'api' => 'Facturación',
        ][$key] ?? ucfirst($key);
    }
}
