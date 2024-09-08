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
//    public function index(Request $request)
//    {
//        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
//        $root = $this->convertCategoriesForPRComponent($root->toArray());
//        return Inertia::render('Categories', ['categories' => $root[0]['children']]);
//    }

    public function tree(Request $request, $rootLabel = 'root', $parentId = null)
    {
        if(empty($parentId)) {
            $root = Category::where('label', $rootLabel)->whereNull('parent_id')->with('children')->first()->toArray();
        }else{
            $root = Category::where('label', $rootLabel)->where('parent_id', $parentId)->with('children')->first()->toArray();
        }
        if($request->hasHeader('x-response-format')) {
            if ($request->header('x-response-format') == 'primereact') {
                $root = $this->convertCategoriesForPRComponent($root);
            }
        }
        return $this->_response($request, $root);
    }

    public function createSibling(Request $request, $key, $name)
    {
        return $this->_response($request, 'OK');
    }
    public function test()
    {

    }
}
