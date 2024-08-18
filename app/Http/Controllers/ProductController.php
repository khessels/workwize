<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());
        return Inertia::render('Products', ['categories' => $root['root'][0]['children']]);
    }
    public function show( Request $request): Response
    {
        $roles = ['poblic'];
        $cartsHistoryCount = 0;
        $salesCount = 0;
        $products = [];
        if( Auth::check()){
            $roles =  Auth::user()->roles->pluck('name')->toArray();
        }
//        if(in_array('supplier', $roles) || in_array('admin', $roles)){
//            $products = Product::orderBy('id', 'ASC')->with('tags.tag.topic')->get()->toArray();
//            $salesCount = $this->getCartsHistoryAllCount();
//        }else{
//            $products = Product::where('stock', '>', 0)->where('active', 'YES')->orderBy('id', 'ASC')->get()->toArray();
//        }

        if(in_array('customer', $roles)){
            $cartsHistoryCount = $this->getCartsHistoryCount();
        }

        // create tag labels
//        foreach($products as $key => $product){
//            if( ! empty( $product['tags']) ){
//                $tags = [];
//                foreach( $product['tags'] as $tag ){
//                    $tagName = $tag['tag']['name'];
//                    $topic =  $tag['tag']['topic']['name'];
//                    $tags[] = $topic . '.' . $tagName;
//                }
//                $products[$key]['tag_labels'] = implode(', ', $tags);
//            }
//        }
        $cartItemsCount = $this->getCartItemsCount();

        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());

        return Inertia::render('Products', [
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
//            'products' => $products,
            'categories' => $root,
            'cartsHistoryCount' => $cartsHistoryCount,
            'salesCount' => $salesCount,
            'cartItemsCount' => $cartItemsCount,
        ]);
    }
    public function showSales( Request $request): Response
    {
        $usersSales = User::has( 'carts.items')->with( 'carts.items.product')->get();
        return Inertia::render('Sales', ['sales' => $usersSales]);
    }
    public function delete( Request $request, $productId)
    {
        // only delete a product if it has no sales. If it has no sales, then also remove it from any cart
        $soldItems = CartItem::where( 'product_id', $productId)->with( [ 'cart'=>function($query){
            $query->where( 'paid','YES');
        }])->get();
        $statusText = 'NOT DELETED';
        if( $soldItems->count() == 0){
            Product::destroy( $productId);
            $statusText = 'DELETED';
        }
        return $this->_response( $request, $statusText);
    }
    public function setActiveState( Request $request): \Illuminate\Http\Response
    {
        $data = $request->validate( [
            'id' => 'required',
            'active' => 'required',
        ]);
        $product = Product::find( $data[ 'id']);
        if( strtoupper( $data[ 'active']) == 'TOGGLE'){
            $product->active = $product->active == 'YES' ? 'NO' : 'YES';
        }else{
            $product->active = $data[ 'active'];
        }
        $product->save();
        return response()->noContent();
    }
    public function setStock( Request $request): \Illuminate\Http\Response
    {
        $product = Product::find( $request->productId);
        $product->stock = $request->quantity;
        $product->save();
        return response()->noContent();
    }
    public function update( Request $request): \Illuminate\Http\Response
    {
        $data = $request->validate( [
            'id' =>'required',
            'name' =>'nullable',
            'stock' =>'nullable',
            'price' =>'nullable',
            'active' =>'nullable',
        ]);
        $product = Product::find( $data[ 'id']);
        if( isset( $data[ 'name'])){
            $product->name = $data[ 'name'];
        }
        if( isset( $data[ 'stock'])){
            $product->stock = $data[ 'stock'];
        }
        if( isset( $data[ 'active'])){
            $product->active = $data[ 'active'];
        }
        if( isset( $data[ 'price'])){
            $product->price = $data[ 'price'];
        }
        $product->save();
        return response()->noContent();
    }
    public function create( Request $request): \Illuminate\Http\Response
    {
        $all = $request->all();
        DB::transaction( function() use ( $request){
            $data = $request->validate([
                'name' =>'required',
                'category' =>'nullable',
                'categories' =>'nullable',
                'stock' =>'required',
                'price' =>'required',
                'active' =>'required',
                'tags'  => 'nullable',
            ]);
            $product = new Product( $data);
            $product->save();
            if( ! empty( $data[ 'categories'])){
                foreach( $data[ 'categories'] as $key => $category){
                    if($category['checked']){
                        $ids = explode('-', $key);
                        $productCategory = ['id' => $ids[0], 'parent_id' => $ids[1], 'product_id' => $product->id];
                        $oProductCategory = new ProductCategory($productCategory);
                        $oProductCategory->save();
                    }
                }
            }
            if( ! empty( $data[ 'tags'])){
                foreach( $data[ 'tags'] as $tag){
                    $tagComponents = explode('.', $tag);
                    $productTag = new ProductTag(['product_id' => $product->id, 'tag_id' => $tagComponents[1]]);
                    $productTag->save();
                }
            }
        });

        return response()->noContent();
    }
    public function getByCategoryKey(Request $request, $key): \Illuminate\Http\JsonResponse
    {
        $exploded = explode('-', $key);
        $parentId = $exploded[1];
        $id = $exploded[0];
        $productIds = ProductCategory::where('id', $id)->where('parent_id', $parentId)->get()->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->with('tags.tag.topic')->get()->toArray();
        foreach($products as $key => $product){
            if( ! empty( $product['tags']) ){
                $tags = [];
                foreach( $product['tags'] as $tag ){
                    $tagName = $tag['tag']['name'];
                    $topic =  $tag['tag']['topic']['name'];
                    $tags[] = $topic . '.' . $tagName;
                }
                $products[$key]['tag_labels'] = implode(', ', $tags);
            }
        }
        return response()->json($products);
    }
}
