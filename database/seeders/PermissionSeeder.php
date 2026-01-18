<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        return;
    }
        // Reset cached roles and permissions
       /* app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'pos.view']);
        Permission::create(['name' => 'pos.details']);
        Permission::create(['name' => 'Category_delete']);
        Permission::create(['name' => 'Category_store']);
        Permission::create(['name' => 'Role_index']);
        Permission::create(['name' => 'Role_update']);
        Permission::create(['name' => 'Role_delete']);
        Permission::create(['name' => 'Role_store']);
        Permission::create(['name' => 'Product_index']);
        Permission::create(['name' => 'Product_update']);
        Permission::create(['name' => 'Product_delete']);
        Permission::create(['name' => 'Product_store']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Seller']);
        $role1->givePermissionTo('pos.view');
        $role1->givePermissionTo('pos.details');

        $role2 = Role::create(['name' => 'Employee']);
        $role2->givePermissionTo('pos.view');
        $role2->givePermissionTo('pos.details');
        $role2->givePermissionTo('products.index');
        $role2->givePermissionTo('products.update');
        $role2->givePermissionTo('products.delete');
        $role2->givePermissionTo('products.store');

        $role3 = Role::create(['name' => 'Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Gerson Ruano',
            'email' => 'toge619@gmail.com',
        ]);
        $user->assignRole($role3);
    }

        /*{
            // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // create permissions
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

            // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


            // create roles and assign created permissions

            // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit articles');

            // or may be done by chaining
        $role = Role::create(['name' => 'moderator'])
        ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
        }*/

}
