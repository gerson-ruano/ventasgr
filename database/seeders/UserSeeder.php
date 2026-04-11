<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $sellerRole = Role::firstOrCreate(['name' => 'Seller']);
        $adminUser = User::create([
            'name'=>'Administrador',
            'phone'=>'23423424',
            'email'=>'admin@ventasgr.com',
            'profile'=>'Admin',
            'status'=>'Active',
            'tema'=> 1,
            'password'=>bcrypt('12341234')
        ]);
        $adminUser->assignRole($adminRole);
        $employeeUser = User::create([
            'name'=>'Empleado',
            'phone'=>'10295343',
            'email'=>'employee@ventasgr.com',
            'profile'=>'Employee',
            'status'=>'Active',
            'tema'=> 1,
            'password'=>bcrypt('56785678')
        ]);
        $employeeUser->assignRole($employeeRole);
        $sellerUser = User::create([
            'name'=>'Vendedor',
            'phone'=>'12345678',
            'email'=>'seller@ventasgr.com',
            'profile'=>'Seller',
            'status'=>'Active',
            'tema'=> 1,
            'password'=>bcrypt('56785678')
        ]);
        $sellerUser->assignRole($sellerRole);
    }
}
