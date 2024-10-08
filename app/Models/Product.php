<?php

namespace App\Models;

use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductPrice;
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'stock', 'active'];
    use HasFactory;

    public function tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }
    public function ProductTags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

}
