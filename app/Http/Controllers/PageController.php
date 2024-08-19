<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use App\Models\Product;
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

class PageController extends Controller
{
    public function index(Request $request)
    {
        $roles = ['public'];
        $cartsHistoryCount = 0;
        $salesCount = 0;
        $products = null;
        if( Auth::check()){
            $roles =  Auth::user()->roles->pluck('name')->toArray();
        }
        if(in_array('supplier', $roles) || in_array('supplier', $roles)){
            $products = Product::orderBy('name', 'ASC')->get()->toArray();
            $salesCount = $this->getCartsHistoryAllCount();
        }

        if(in_array('customer', $roles)){
            $cartsHistoryCount = $this->getCartsHistoryCount();
        }

        if(isNull($products)){
            if( in_array( 'supplier', $roles) || in_array( 'admin', $roles)){
                $products = Product::orderBy('name', 'ASC')->get()->toArray();
            }else {
                $products = Product::where('stock', '>', 0)->where('active', 'YES')->orderBy('id', 'ASC')->get()->toArray();
            }
        }
        $cartItemsCount = $this->getCartItemsCount();

        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        //$children = $root->children->toArray();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());

        return Inertia::render('Welcome', [
            'products' => $products,
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
