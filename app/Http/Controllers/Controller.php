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

    protected function convertCategoriesForPRComponent( $categories, $childKeyName = 'children'){
        $cats = $this->recursiveAddKey( [ $categories], 0, $childKeyName)[0];
        return $this->recursiveAddURL( 1, [ $cats], 0, $childKeyName );
    }
    protected function recursiveAddURL($parentId,  $array, $depth = 0, $childKeyName = 'children')
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
                $item[ $childKeyName] = $this->recursiveAddURL( $parentId, $item[ $childKeyName], $depth + 1, $childKeyName);
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

}
