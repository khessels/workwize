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
    protected function convertCategoriesForPRComponent( $categories, $childKeyName = 'children'){
        $cats = $this->recursiveAddKey( [ $categories], 0, $childKeyName)[0];
        return $this->recursiveAddURL( [ $cats], 0, $childKeyName, 1);
    }
    protected function recursiveAddURL( $array, $depth = 0, $childKeyName = 'children', $parentId)
    {
        $newArray = [];
        foreach( $array as $item)
        {
            //$item[ 'url'] =  "/products/category/key/" . $item[ 'id'];
            if( ! empty( $item[ 'parent_id']) && $item[ 'parent_id'] != $parentId ){
                $item[ 'url'] =  "/products/category/" . $item[ 'id'] ;
                $item[ 'url'] .= '/' . $item[ 'parent_id'];
            }

            if ( count( $item[ $childKeyName]) > 0 && ! empty( $item[ $childKeyName]))
            {
                $item[ $childKeyName] = $this->recursiveAddURL( $item[ $childKeyName], $depth + 1, $childKeyName, $parentId);
            }
            $newArray[] = $item;
        }
        return $newArray;
    }

    protected function recursiveAddKey( $array, $depth = 0, $childKeyName = 'children')
    {
        $newArray = [];
        foreach( $array as $item)
        {
            $item[ 'key'] = (string) $item[ 'id'];
            if( ! empty( $item[ 'parent_id'])){
                $item[ 'key'] .= '-' . $item[ 'parent_id'];
            }

            if ( count( $item[ $childKeyName]) > 0 && ! empty( $item[ $childKeyName]))
            {
                $item[ $childKeyName] = $this->recursiveAddKey( $item[ $childKeyName], $depth + 1, $childKeyName);
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
