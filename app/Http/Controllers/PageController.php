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
class PageController extends Controller
{
    public function index(Request $request)
    {
        $roles = [];
        if(Auth::check()){
            $roles =  Auth::user()->roles->pluck('name')->toArray();
        }
        if(in_array('supplier', $roles)){
            $products = Product::orderBy('name', 'ASC')->get()->toArray();
        }else{
            $products = Product::where('stock', '>', 0)->where('Active', 'YES')->orderBy('name', 'ASC')->get()->toArray();
        }

        //die(json_encode($products));
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'roles' => $roles,
            'products' => $products,
        ]);
    }
    public function dashboard(Request $request)
    {
        $roles =  Auth::user()->roles->pluck('name')->toArray();
        return Inertia::render('Dashboard', ['roles' => $roles]);
    }
}
