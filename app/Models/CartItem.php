<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table="cart_items";
    protected $fillable = ['cart_id', 'product_id', 'price', 'quantity'];
    public function product(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\CartItem', 'cart_id', 'id');
    }
    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Cart', 'cart_id', 'id');
    }

}
