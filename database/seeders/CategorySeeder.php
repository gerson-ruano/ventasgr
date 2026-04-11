<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name'=> 'CURSOS',
            'image'=> '686c6bd17c75d_.png'
        ]);
        Category::create([
            'name'=> 'ZAPATOS',
            'image'=> '666dfed328708_.webp'
        ]);
        Category::create([
            'name'=> 'CELULARES',
            'image'=> '69da9b7bebb78_.jpg'
        ]);
        Category::create([
            'name'=> 'COMPUTADORAS',
            'image'=> '681f973dba57d_.png'
        ]);
        Category::create([
            'name'=> 'STOCK_BAJO',
            'image'=> 'IMAGE.png'
        ]);
    }
}
