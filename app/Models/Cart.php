<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table    = "carts";
    protected $fillable = ['user_id', 'paid', 'total'];
    protected $casts = [
        'created_at' => 'datetime:D d M Y H:i:s',
        'updated_at' => 'datetime:D d M Y H:i:s'
    ];

    public function createdAtFormatted(): Attribute
    {
        return Attribute::get(fn() => Carbon::parse($this->attributes['created_at'])->format('m-d-Y'));
    }
    public function updatedAtFormatted(): Attribute
    {
        return Attribute::get(fn() => Carbon::parse($this->attributes['updated_at'])->format('m-d-Y'));
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\CartItem', 'cart_id', 'id');
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
