<?php

namespace App\Models;

use App\Models\SaleDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'cost',
        'price',
        'stock',
        'alerts',
        'image',
        'category_id',
        'discount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function sales()
    {
        return $this->hasManyThrough(Sale::class, SaleDetail::class, 'product_id', 'id', 'id', 'sale_id');
    }

    public function getImagenAttribute()
    {
        $imagePath = 'storage/products/' . ($this->image ? $this->image : 'noimg.jpg');

        // Verifica si la imagen existe en la ruta especificada
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        } else {
            // Si la imagen no existe, muestra la imagen por defecto
            return asset('img/noimg.jpg');
        }
    }

}
