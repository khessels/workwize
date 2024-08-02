<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    private function with(bool $withProduct = false)
    {
        $with = 'items';
        if($withProduct){
            $with = 'items.product';
        }
        return $with;
    }
    protected function getCart(bool $withProduct = false)
    {
        $with = $this->with($withProduct);
        return Cart::where('user_id', Auth::id())->whereIn('paid', ['NO', 'PROCESSING'])->with($with)->first();
    }
    protected function getCartItemsCount(bool $withProduct = false)
    {
        $cart = $this->getCart($withProduct);
        if(empty($cart)){
            return 0;
        }
        return $cart->items->count();
    }
    protected function getCartsHistory(bool $withProduct = false)
    {
        $with = $this->with($withProduct);
        return Cart::where('user_id', Auth::id())->whereIn('paid', ['YES'])->with($with)->get();
    }
    protected function getCartsHistoryAll(bool $withProduct = false, bool $withUser = false)
    {
        $with = $this->with($withProduct);
        if($withUser){
            return Cart::whereIn('paid', ['YES'])->with($with)->with('user')->get();
        }
        return Cart::whereIn('paid', ['YES'])->with($with)->get();
    }
    protected function getCartsHistoryCount()
    {
        $cart = Cart::where('user_id', Auth::id())->whereIn('paid', ['YES'])->with('items')->get();
        if(empty($cart)){
            return 0;
        }
        return $cart->count();
    }
    protected function getCartsHistoryAllCount()
    {
        $cart = Cart::whereIn('paid', ['YES'])->with('items')->get();
        if(empty($cart)){
            return 0;
        }
        return $cart->count();
    }
}
