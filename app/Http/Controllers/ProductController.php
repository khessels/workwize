<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
        $usersSales = User::has('carts')->with('carts.items.product')->get();
        return Inertia::render('Sales', ['sales' => $usersSales]);
    }

}
