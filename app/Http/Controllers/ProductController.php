<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{

    public function show(Request $request)
    {
        return Inertia::render('Products');
    }
    public function showSales(Request $request)
    {
        $usersSales = User::has('carts.items')->with('carts.items.product')->get();
        return Inertia::render('Sales', ['sales' => $usersSales]);
    }
    public function delete(Request $request, $productId)
    {
        // only delete a product if it has no sales. If it has no sales, then also remove it from any cart
        $soldItems = CartItem::where('product_id', $productId)->with(['cart'=>function($query){
            $query->where('paid','YES');
        }])->get();

        if($soldItems->count() == 0){
            Product::destroy($productId);
        }
        return response()->noContent();
    }
    public function setActiveState(Request $request)
    {
        $data = $request->validate([
            'id' =>'required',
            'active' =>'required',
        ]);
        $product = Product::find($data['id']);
        if(strtoupper($data['active']) == 'TOGGLE'){
            $product->active = $product->active == 'YES' ? 'NO' : 'YES';
        }else{
            $product->active = $data['active'];
        }
        $product->save();
        return response()->noContent();
    }
    public function setStock(Request $request)
    {
        $product = Product::find($request->productId);
        $product->stock = $request->quantity;
        $product->save();
        return response()->noContent();
    }
    public function update(Request $request){
        $data = $request->validate([
            'id' =>'required',
            'name' =>'nullable',
            'stock' =>'nullable',
            'price' =>'nullable',
            'active' =>'nullable',
        ]);
        $product = Product::find($data['id']);
        if(isset($data['name'])){
            $product->name = $data['name'];
        }
        if(isset($data['stock'])){
            $product->stock = $data['stock'];
        }
        if(isset($data['active'])){
            $product->active = $data['active'];
        }
        if(isset($data['price'])){
            $product->price = $data['price'];
        }
        $product->save();
        return response()->noContent();
    }
    public function create(Request $request){
        $data = $request->validate([
            'name' =>'required',
            'stock' =>'required',
            'price' =>'required',
            'active' =>'required',
        ]);
        $product = new Product($data);
        $product->save();
        return response()->noContent();
    }

}
