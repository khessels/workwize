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
        $categories = Category::all();
        return Inertia::render('Categories', ['categories' => $categories]);
    }
    public function seed(){
        $categories = [];
        $category = [ 'english' => 'resistors', 'spanish' => 'resistencias', 'tag' => 'resistors', 'active' => 'YES', 'parent' => [
            ['english' => 'through hole', 'spanish' => 'por wekko', 'tag' => 'through-hole', 'active' => 'YES', 'parent' => [
                ['english' => '1/8 Watt', 'spanish' => '1/8 Watt', 'tag' => '1_8-Watt', 'active' => 'YES'],
                ['english' => '1/4 Watt', 'spanish' => '1/4 Watt', 'tag' => '1_4-Watt', 'active' => 'YES'],
            ]],
            ['english' => 'SMD', 'spanish' => 'SMD', 'tag' => 'smd', 'active' => 'YES', 'parent' => [
                ['english' => '1206', 'spanish' => '1206', 'tag' => 'smd-1206', 'active' => 'YES'],
                ['english' => '604', 'spanish' => '604', 'tag' => 'smd-604', 'active' => 'YES'],
            ]],
        ]
        ];
        $categories[] = $category;
        foreach($categories as $categoryIndex => $category){
            $children = $category['parent'];
            unset($category['parent']);
            $category['id'] = $categoryIndex +1;
            $parentCategory = Category::create($category);
            $parentCategory->save();
            $ParentID = $parentCategory->id;
            error_log(1);
            foreach($children as $child){
                $grandChildren = $child['parent'];
                unset($child['parent']);
                $child['parent_id'] = $ParentID;
                $child['id'] = $categoryIndex +1;
                $childCategory = Category::create($child);
                $ChildID = $childCategory->id;
                foreach($grandChildren as $grandChild){
                    $grandChild['parent_id'] = $ChildID;
                    $grandChild['id'] = $categoryIndex +1;
                    Category::create($grandChild);
                }
            }
        }

    }
}
