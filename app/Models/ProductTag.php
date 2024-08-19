<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
class ProductTag extends Model
{
    protected $table = 'product_tags';
    protected $fillable = ['id', 'tag_id', 'product_id'];
    use HasFactory;

    public function tag(){
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }
    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
