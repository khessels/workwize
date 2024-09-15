<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\NoReturn;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = $this->getCart(true);
        if(empty($cart)){
            return redirect::route('welcome');
        }
        // todo: add provision to Cart page that disables checkout because the cart is currently being processed
        $cartsHistoryCount = $this->getCartsHistoryCount();

        return Inertia::render('Cart', ['cart' => $cart, 'cartsHistoryCount' => $cartsHistoryCount, 'cartItemsCount' => $cart->items->count()] );
    }
    public function showHistory(Request $request): Response
    {
        $carts = $this->getCartsHistory(true);
        return Inertia::render('CartsHistory', ['carts' => $carts]);
    }
    public function addItem(Request $request): \Illuminate\Http\Response
    {
        //error_log( print_r($request->all(), true));
        // todo: validate request
        $cart = $this->getCart();
        if( ! $cart){
            $cart = new Cart();
            $cart->user_id = Auth::id();
            $cart->save();
        }else if($cart->paid == 'PROCESSING'){
            // cart is being processed
            return response()->noContent()->setStatusCode(300);
        }
        $product = Product::where('id', $request->id)->first();

        // if in cart increase quantity, otherwise add item
        if( $cart->items->count() > 0 ) {
            foreach ($cart->items as $item) {
                if ($item->product_id == $request->id) {
                    if ($product->stock >= $request->quantity) {
                        $item->quantity += $request->quantity;
                        $item->save();
                        return response()->noContent()->setStatusCode(200);
                    }
                }
            }
        }
        // did not find an existing article
        if ($product->stock >= $request->quantity) {
            $item = new CartItem();
            $item->cart_id = $cart->id;
            $item->product_id = $request->id;
            $item->quantity = $request->quantity;
            $item->save();
            return response()->noContent()->setStatusCode(200);
        }
        return response()->noContent()->setStatusCode(300);
    }
    public function removeItem(Request $request, $id): \Illuminate\Http\Response
    {
        // todo: validate request
        $cart = $this->getCart();
        foreach($cart->items as $item){
            if($id == $item->id){
                $item->delete();
                return response()->noContent()->setStatusCode(200);
            }
        }
        return response()->noContent()->setStatusCode(300);
    }
    private function with( bool $withProduct = false)
    {
        $with = 'items';
        if( $withProduct){
            $with = 'items.product';
        }
        return $with;
    }

    public function getCart( bool $withProduct = false)
    {
        $with = $this->with( $withProduct);
        return Cart::where( 'user_id', Auth::id())->whereIn( 'paid', [ 'NO', 'PROCESSING'])->with( $with)->first();
    }
    public function cartItemsCount( bool $withProduct = false)
    {
        $cart = $this->getCart( $withProduct);
        if( empty( $cart)){
            return 0;
        }
        return $cart->items->count();
    }
    public function getCartsHistory( bool $withProduct = false)
    {
        $with = $this->with( $withProduct);
        return Cart::where( 'user_id', Auth::id())->whereIn( 'paid', [ 'YES'])->with( $with)->get();
    }
    public function getCartsHistoryAll( bool $withProduct = false, bool $withUser = false)
    {
        $with = $this->with( $withProduct);
        if( $withUser){
            return Cart::whereIn( 'paid', [ 'YES'])->with( $with)->with( 'user')->get();
        }
        return Cart::whereIn( 'paid', [ 'YES'])->with( $with)->get();
    }
    public function getCartsHistoryCount()
    {
        $cart = Cart::where( 'user_id', Auth::id())->whereIn( 'paid', [ 'YES'])->with( 'items')->get();
        if(empty( $cart)){
            return 0;
        }
        return $cart->count();
    }
    public function getCartsHistoryAllCount()
    {
        $cart = Cart::whereIn( 'paid', [ 'YES'])->with( 'items')->get();
        if( empty( $cart)){
            return 0;
        }
        return $cart->count();
    }

}
