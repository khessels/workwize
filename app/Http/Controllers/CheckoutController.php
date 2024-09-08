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

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = $this->getCart(true);
        if(empty($cart)){
            return redirect::route('welcome');
        }
        // todo: add provision to Cart page that disables checkout because the cart is currently being processed
        $cartsHistoryCount = $this->getCartsHistoryCount();

        return Inertia::render('Checkout', ['cart' => $cart, 'cartsHistoryCount' => $cartsHistoryCount, 'cartItemsCount' => $cart->items->count()] );
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
