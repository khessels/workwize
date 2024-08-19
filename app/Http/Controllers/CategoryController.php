<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());
        return Inertia::render('Categories', ['categories' => $root['root'][0]['children']]);
    }
    public function tree($rootLabel = 'root', $parentId = null){
        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());
        return response()->json( $root[0]['children']);
    }
    public function createSibling(Request $request, $key, $name)
    {
        return $this->_response($request, 'OK');
    }
    public function test()
    {

    }
}
