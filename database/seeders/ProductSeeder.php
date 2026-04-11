<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name'=>'LARAVEL',
            'cost'=> 200,
            'price'=> 350,
            'barcode'=>'101',
            'stock'=> 100,
            'alerts'=> 10,
            'category_id'=> 1,
            'image'=>'686c6bd17c75d_.png'
        ]);
        Product::create([
            'name'=>'RUNING',
            'cost'=> 600,
            'price'=> 850,
            'barcode'=>'102',
            'stock'=> 100,
            'alerts'=> 10,
            'category_id'=> 2,
            'image'=>'tenis.png'
        ]);
        Product::create([
            'name'=>'IPHONE 13',
            'cost'=> 4200,
            'price'=> 7530,
            'barcode'=>'103',
            'stock'=> 130,
            'alerts'=> 10,
            'category_id'=> 3,
            'image'=>'69da9b7bebb78_.jpg'
        ]);
        Product::create([
            'name'=>'PC GAMER',
            'cost'=> 2200,
            'price'=> 5730,
            'barcode'=>'104',
            'stock'=> 156,
            'alerts'=> 10,
            'category_id'=> 4,
            'image'=>'public/products/681f973dba57d_.png'
        ]);
        Product::create([
            'name'=>'STOCK_BAJO',
            'cost'=> 50,
            'price'=> 90,
            'barcode'=>'105',
            'stock'=> 9,
            'alerts'=> 10,
            'category_id'=> 5,
            'image'=>'storage/app/public/products/681f973dba57d_.png'
        ]);
    }
}
