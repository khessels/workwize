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
    public function show(Request $request): Response
    {
        $cart = $this->getCart(true);
        //die(json_encode($cart));
        // todo: add provision to Cart page that disables checkout because the cart is currently being processed
        $carts = $this->getCartsHistory();
        return Inertia::render('Cart', ['cart' => $cart, 'cartsHistoryCount' => $carts->count()] );
    }
    public function showHistory(Request $request): Response
    {
        $carts = $this->getCartsHistory();
        return Inertia::render('CartsHistory', ['carts' => $carts]);
    }
    #[NoReturn] public function addItem(Request $request): \Illuminate\Http\Response
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
                    if ($product->stock >= 1) {
                        $item->quantity += 1;
                        $item->save();
                        return response()->noContent()->setStatusCode(200);
                    }
                }
            }
        }
        // did not find an existing article
        if ($product->stock >= 1) {
            $item = new CartItem();
            $item->cart_id = $cart->id;
            $item->product_id = $request->id;
            $item->quantity = 1;
            $item->save();
            return response()->noContent()->setStatusCode(200);
        }
        return response()->noContent()->setStatusCode(300);
    }
    #[NoReturn] public function removeItem(Request $request, $id): \Illuminate\Http\Response
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
    private function getCart(bool $withProduct = false)
    {
        $with = 'items';
        if($withProduct){
            $with = 'items.product';
        }
        return Cart::where('user_id', Auth::id())->whereIn('paid', ['NO', 'PROCESSING'])->with($with)->first();
    }
    private function getCartsHistory()
    {
        return Cart::where('user_id', Auth::id())->whereIn('paid', ['YES'])->with('items')->get();
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
            $cart = new Cart();
            $cart->user_id = Auth::id();
            $cart->save();
        }else if($cart->paid == 'PROCESSING'){
            // cart is being processed
            return response()->noContent()->setStatusCode(300);
        }

        // set cart in processing mode, so we can not accidentally check out with another process
        $cart->paid = 'PROCESSING';
        $cart->save();

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

        });

        error_log('Sending redirect');
        return redirect::route('welcome');
    }

}
