<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function CustomerList(Request $request)
    {
        $products = Product::where('quantity', '>')->where('Active', 'YES')->orderBy('name', 'ASC')->get()->toArray();
        return Inertia::render('CustomerList', ['products' => $products]);
    }
    public function SupplierList(Request $request)
    {
        $products = Product::orderBy('name', 'ASC')->get()->toArray();
        return Inertia::render('SupplierList', ['products' => $products]);
    }
}
