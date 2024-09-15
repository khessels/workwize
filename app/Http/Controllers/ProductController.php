<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\ProductTag;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    public function show( Request $request, $categoryId = null, $categoryParentId = null): Response
    {
        return Inertia::render('Products', [
            'categoryId' => $categoryId,
            'categoryParentId' => $categoryParentId
        ]);
    }

    public function filter(Request $request)
    {
        $query = Product::query();
        $query = $query->where('active', 'YES');
        $query = $query->with('tags');

        if($request->filled('tags')){
            $tags =  explode(',', $request->tags);
            $query = $query->with('tags', function($q) use( $tags){
                $q->whereIn('tag_id', $tags);
            });
            $query = $query->whereHas('tags', function($q) use( $tags){
                $q->whereIn('tag_id', $tags);
            });
        }

        $query = $query->with('prices');
        $query = $query->whereHas('prices');

        $products = $query->get();

        if($request->hasHeader('x-response-format')) {
            if ($request->header('x-response-format') == 'primereact') {
                //$root = $this->convertCategoriesForPRComponent($root->toArray());
            }
        }
        return $this->_response( $request, $products);
    }

    public function getById(Request $request, $id)
    {
        $root = Category::where('label', 'root')->whereNull('parent_id')->with('items')->first();
        $root = $this->convertCategoriesForPRComponent($root->toArray(), 'items');

        $product = Product::where('id', $id)->with('tags')->with('prices')->whereHas('prices')->with('productCategories')->first()->toArray();
        return Inertia::render('Product', [
            'product' => $product,
            'categories' => $root
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
                'price' =>'nullable',
                'prices' => 'nullable',
                'active' =>'required',
                'tags'  => 'nullable',
            ]);
            $product = new Product( $data);
            $product->save();

            $priceSaved = false;
            if( !empty( $data['price'])){
                $productPrice = new ProductPrice( ['product_id' => $product->id, 'price' => $data['price'], 'quantity' => 0] );
                $productPrice->save();
                $priceSaved = true;

            }else if( ! empty( $data['prices'])){
                foreach( $data['prices'] as $price){
                    $price = ! empty( $price[ 'price']) ? $price[ 'price'] : null;
                    $discount = ! empty( $price[ 'discount']) ? $price[ 'discount'] : null;
                    $productPrice = new ProductPrice( ['product_id' => $product->id, 'price' => $price, 'discount' => $discount, 'quantity' => 0] );
                    $productPrice->save();
                    $priceSaved = true;
                }
            }
            if( ! $priceSaved){
                return $this->_response('ERROR: No Price(s)');
            }
            if( ! empty( $data[ 'categories'])){
                foreach( $data[ 'categories'] as $key => $category){
                    if($category[ 'checked']){
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
                    $productTag = new ProductTag( [ 'product_id' => $product->id, 'tag_id' => $tagComponents[1]]);
                    $productTag->save();
                }
            }
        });

        return response()->noContent();
    }
    public function getByCategoryKey(Request $request, $key): \Illuminate\Http\JsonResponse
    {
        //die("hello");
        $exploded = explode('-', $key);
        $parentId = $exploded[1];
        $id = $exploded[0];
        $productIds = ProductCategory::where('id', $id)->where('parent_id', $parentId)->get()->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->with(['prices' => function($q) {
            $q->orderBy('quantity', 'asc');
        }])->with('tags.tag.topic')->get()->toArray();
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
