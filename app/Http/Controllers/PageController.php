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
        $roles = [];
        $cartsHistoryCount = 0;
        $salesCount = 0;
        $products = null;
        if(Auth::check()){
            $roles =  Auth::user()->roles->pluck('name')->toArray();
        }

        if(in_array('supplier', $roles)){
            $products = Product::orderBy('name', 'ASC')->get()->toArray();
            $salesCount = $this->getCartsHistoryAllCount();
        }

        if(in_array('customer', $roles)){
            $cartsHistoryCount = $this->getCartsHistoryCount();
        }

        if(isNull($products)){
            $products = Product::where('stock', '>', 0)->where('Active', 'YES')->orderBy('name', 'ASC')->get()->toArray();
        }

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'roles' => $roles,
            'products' => $products,
            'cartsHistoryCount' => $cartsHistoryCount,
            'salesCount' => $salesCount
        ]);
    }
    public function dashboard(Request $request)
    {
        $roles =  Auth::user()->roles->pluck('name')->toArray();
        return Inertia::render('Dashboard', ['roles' => $roles]);
    }
}
