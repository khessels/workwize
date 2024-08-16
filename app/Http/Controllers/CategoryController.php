<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
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
    public function test()
    {
        $categories = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first()->toArray();


        foreach( $categories as $category){
            $products = Product::factory()->count(20)->create();
            foreach($products as $product){
                $product->parent_id = $category['id'];
                $product->save();
            }
        }
    }
}
