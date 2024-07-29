<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function show(Request $request): Response
    {
        $cart = $this->getCart();
        // todo: add provision to Cart page that disables checkout because the cart is currently being processed
        $carts = $this->getCartsHistory();
        return Inertia::render('Cart', ['cart' => $cart, 'cartsHistoryCount' => $carts->count()] );
    }
    public function showHistory(Request $request): Response
    {
        $carts = $this->getCartsHistory();
        return Inertia::render('CartsHistory', ['carts' => $carts]);
    }
    public function addItem(Request $request): void
    {
        // todo: validate request
        $cart = $this->getCart();
        if( ! $cart){
            $cart = new Cart();
            $cart->user_id = Auth::id();
            $cart->save();
        }else if($cart->paid == 'PROCESSING'){
            // cart is being processed
            response()->noContent()->setStatusCode(303);
        }

        $product = Product::find($request->product_id);

        // if in cart increase quantity, otherwise add item
        if( $cart->items) {
            foreach ($cart->items as $item) {
                if ($item->product_id = $request->product_id) {
                    if ($product->stock >= $request->quantity) {
                        $item->quantity += $request->quantity;
                        $item->save();
                        response()->noContent()->setStatusCode(200);
                    }
                }
            }
        }else{
            $item = new CartItem($request->all());
            $item->cart_id = $cart->id;
            $item->save();
            response()->noContent()->setStatusCode(200);
        }
        response()->noContent()->setStatusCode(304);
    }
    public function removeItem(Request $request): void
    {
        // todo: validate request
        $cart = $this->getCart();
        foreach($cart->items as $item){
            if($request->product_id == $item->product_id){
                $item->delete();
            }
        }
        response()->noContent()->setStatusCode(200);
    }
    private function getCart()
    {
        return Cart::where('user_id', Auth::id())->whereIn('paid', ['NO', 'PROCESSING'])->with('items')->first();
    }
    private function getCartsHistory()
    {
        return Cart::where('user_id', Auth::id())->whereIn('paid', ['YES'])->with('items')->get();
    }
    public function checkOut(Request $request): void
    {
        // todo: validate your cart items are still available in stock (we assume that they are for now !)
        // todo: lock product stock during processing
        // todo: payment (assume we were successful for now)
        // todo: decrease product stock on successfully creating sale
        // todo: set cart to paid
        $cart = $this->getCart();
        if( ! $cart){
            $cart = new Cart();
            $cart->user_id = Auth::id();
            $cart->save();
        }else if($cart->paid == 'PROCESSING'){
            // cart is being processed
            response()->noContent()->setStatusCode(303);
        }

        // set cart in processing mode, so we can not accidentally check out with another process
        $cart->paid = 'PROCESSING';
        $cart->save();

        // we can use the price of the cart items on the moment they are added to the cart or use the current product prices.
        // (Here we will use the current product prices.)

        // validate stock availability
        foreach($cart->items as $item){
            $product = Product::find($item->product_id);
            if($product->stock < $item->quantity){
                response()->noContent()->setStatusCode(204);
            }
        }

        // We have sufficient stock, let's wrap things into a transaction and finalize
        // payment, decrease stock and set cart to paid
        DB::Transaction(function () use ($cart){
            // todo: we do some payment processing here

            // Assuming the payment was successful we decrease the stock
            foreach($cart->items as $item) {
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();

                // we set the cart to paid
                $cart->paid='YES';
                $cart->save();
            }
            // todo: we send some awesome Workwize emails designed by Awesome Workwize designers

            // success
            response()->noContent()->setStatusCode(200);
        }, 5);

        // cart is not being checked out for other reasons (payment failed perhaps ?)
        response()->noContent()->setStatusCode(204);
    }

}
