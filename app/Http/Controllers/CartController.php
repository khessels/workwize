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

    public function checkOut(Request $request)
    {
        // todo: validate your cart items are still available in stock (we assume that they are for now !)
        // todo: lock product stock during processing
        // todo: payment (assume we were successful for now)
        // todo: decrease product stock on successfully creating sale
        // todo: set cart to paid
        $cart = $this->getCart();

        if( ! $cart){
            return redirect::route('welcome');
        }else if($cart->paid == 'PROCESSING'){
            // cart is being processed
            return response()->noContent()->setStatusCode(300);
        }

        // validate stock availability
        foreach($cart->items as $item){
            $product = Product::find($item->product_id);
            if($product->stock < $item->quantity){
                return response()->noContent()->setStatusCode(300);
            }
        }

        // We have sufficient stock, let's wrap things into a transaction and finalize
        // payment, decrease stock and set cart to paid
        DB::Transaction(function () use ($cart){
            // set cart in processing mode, so we can not accidentally check out with another process
            // disabled for now since we don't have to consult external api's
            //$cart->paid = 'PROCESSING';
            //$cart->save();

            // todo: we do some payment processing here

            // Assuming the payment was successful we decrease the stock
            $cartTotal = 0;
            foreach($cart->items as $item) {
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();

                // save the sold price for historical reasons
                $item->price = $product->price;
                $cartTotal += $item->quantity * $product->price;
                $item->save();
            }

            // we set the cart to paid and add total
            $cart->total = $cartTotal;
            $cart->paid='YES';
            $cart->save();

            // todo: we send some awesome myshop emails designed by Awesome myshop designers

        });

        return redirect::route('welcome');
    }
}
