<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Product.php
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'price', 'stock', 'brand', 'specs', 'thumbnail'];

    protected $casts = ['specs' => 'array'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
