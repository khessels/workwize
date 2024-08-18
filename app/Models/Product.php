<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductTag;
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'stock', 'price', 'active'];
    use HasFactory;

    public function tags()
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }
}
