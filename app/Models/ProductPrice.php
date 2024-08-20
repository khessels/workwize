<?php

namespace App\Models;

use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_price';
    protected $fillable = [ 'product_id', 'quantity', 'price'];
    use HasFactory;
}
