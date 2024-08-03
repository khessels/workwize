<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
        $roles = ['poblic'];
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
                $products = Product::where('stock', '>', 0)->where('Active', 'YES')->orderBy('id', 'ASC')->get()->toArray();
            }
        }
        $cartItemsCount = $this->getCartItemsCount();

        return Inertia::render('Welcome', [
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'products' => $products,
            'cartsHistoryCount' => $cartsHistoryCount,
            'salesCount' => $salesCount,
            'cartItemsCount' => $cartItemsCount,
        ]);
    }
    public function dashboard(Request $request)
    {
        $roles =  Auth::user()->roles->pluck('name')->toArray();
        return Inertia::render('Dashboard', ['roles' => $roles]);
    }
}
