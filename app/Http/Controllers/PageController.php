<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Application;
//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\isNull;

class PageController extends CartController
{
    public function index(Request $request)
    {
        $roles = ['public'];
        $cartsHistoryCount = 0;
        $salesCount = 0;

        if( Auth::check()){
            $roles =  Auth::user()->roles->pluck('name')->toArray();
        }
        if(in_array('supplier', $roles) || in_array('supplier', $roles)){
            $salesCount = $this->getCartsHistoryAllCount();
        }

        if(in_array('customer', $roles)){
            $cartsHistoryCount = $this->getCartsHistoryCount();
        }

        $cartItemsCount = $this->cartItemsCount();

        $root = Category::where('label', 'root')->whereNull('parent_id')->with('items')->first();
        $root = $this->convertCategoriesForPRComponent($root->toArray(), 'items');

        $topic = Topic::where('name', 'LANDING')->with('tags')->first();
        foreach( $topic->tags as $tag ){
            $productTags = ProductTag::where('tag_id', $tag->id)->with('product.productCategories')->get();
            $productsByTag[] = ['tag' => $tag->name, 'productTags' => $productTags];
        }

        return Inertia::render('Welcome', [
            'productsByTag' => empty($productsByTag) ? [] : $productsByTag,
            'categories' => $root,
            'cartsHistoryCount' => $cartsHistoryCount,
            'salesCount' => $salesCount,
            'cartItemsCount' => $cartItemsCount,
        ]);
    }
    public function dashboard(Request $request)
    {
        return Inertia::render('Dashboard');
    }
}
