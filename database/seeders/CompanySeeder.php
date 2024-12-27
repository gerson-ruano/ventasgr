<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name'=> 'Ventas GR',
            'address'=> 'Ciudad de Guatemala',
            'email'=> 'ventasgr@gmail.com',
            'phone'=> '22334455',
            'nit'=> '123456789',
            'image'=> 'img/ventasgr_logo.png'
        ]);
    }
}
