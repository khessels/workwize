<?php

namespace App\Models;

use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'stock', 'active'];
    use HasFactory;

    public function tags()
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }
}
