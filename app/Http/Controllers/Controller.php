<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use stdClass;

abstract class Controller
{
    protected function _response( $request = null, $data = null, $headers = []): \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
    {
        if( empty( $data)) {
            return response()->noContent();
        }
        if( is_string( $data)){
            $response = response( $data);
        }else{
            $response = response()->json( $data);
        }
        if( sizeof( $headers) > 0){
            foreach($headers as $key => $value){
                $response = $response->header( $key, $value);
            }
        }
        return $response;
    }

    private function with( bool $withProduct = false)
    {
        $with = 'items';
        if( $withProduct){
            $with = 'items.product';
        }
        return $with;
    }
    protected function convertCategoriesForTreeSelect( $categories){
        $mutatedCategories = $this->recursiveMutateCategories( [ $categories]);
        return [ 'root' => $mutatedCategories];
    }
    protected function recursiveMutateCategories( $array, $depth = 0)
    {
        $newArray = [];
        foreach( $array as $index => $item)
        {
            $item[ 'key'] = (string) $item[ 'id'];
            if( ! empty( $item[ 'parent_id'])){
                $item[ 'key'] .= '-' . $item[ 'parent_id'];
            }
            unset($item[ 'id']);
            unset($item[ 'parent_id']);
            if ( count( $item[ 'children']) > 0 && ! empty( $item[ 'children']))
            {
                $item[ 'children'] = $this->recursiveMutateCategories( $item[ 'children'], $depth + 1);
            }
            $newArray[] = $item;
        }
        return $newArray;
    }


    protected function getCart( bool $withProduct = false)
    {
        $with = $this->with( $withProduct);
        return Cart::where( 'user_id', Auth::id())->whereIn( 'paid', [ 'NO', 'PROCESSING'])->with( $with)->first();
    }
    protected function getCartItemsCount( bool $withProduct = false)
    {
        $cart = $this->getCart( $withProduct);
        if( empty( $cart)){
            return 0;
        }
        return $cart->items->count();
    }
    protected function getCartsHistory( bool $withProduct = false)
    {
        $with = $this->with( $withProduct);
        return Cart::where( 'user_id', Auth::id())->whereIn( 'paid', [ 'YES'])->with( $with)->get();
    }
    protected function getCartsHistoryAll( bool $withProduct = false, bool $withUser = false)
    {
        $with = $this->with( $withProduct);
        if( $withUser){
            return Cart::whereIn( 'paid', [ 'YES'])->with( $with)->with( 'user')->get();
        }
        return Cart::whereIn( 'paid', [ 'YES'])->with( $with)->get();
    }
    protected function getCartsHistoryCount()
    {
        $cart = Cart::where( 'user_id', Auth::id())->whereIn( 'paid', [ 'YES'])->with( 'items')->get();
        if(empty( $cart)){
            return 0;
        }
        return $cart->count();
    }
    protected function getCartsHistoryAllCount()
    {
        $cart = Cart::whereIn( 'paid', [ 'YES'])->with( 'items')->get();
        if( empty( $cart)){
            return 0;
        }
        return $cart->count();
    }
}
