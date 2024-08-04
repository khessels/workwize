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

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = [];
        $categoryParents = Category::whereNull('parent_id')->get();
        foreach($categoryParents as $parent){
            $category['parent'] = $parent;
            $category['children'] = $parent->childrenRecursive;
            $categories[] = $category;

        }
        return Inertia::render('Categories', ['categories' => $categories]);
    }
    public function test(){

    }
}
