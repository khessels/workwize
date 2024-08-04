<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    // https://blog.ghanshyamdigital.com/building-a-self-referencing-model-in-laravel-a-step-by-step-guide
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [];
        $category = [ 'name' => 'resistors', 'active' => 'YES', 'parent' => [
                ['name' => 'through hole', 'active' => 'YES', 'parent' => [
                    ['name' => '1/8 Watt', 'active' => 'YES'],
                    ['name' => '1/4 Watt', 'active' => 'YES'],
                ]],
                ['name' => 'SMD', 'active' => 'YES', 'parent' => [
                    ['name' => '1206', 'active' => 'YES'],
                    ['name' => '604', 'active' => 'YES'],
                ]],
            ]
        ];
        $categories[] = $category;
        foreach($categories as $categoryIndex => $category){
            $children = $category['parent'];
            unset($category['parent']);
            $parentCategory = Category::create($category);
            $parentCategory->save();
            $ParentID = $parentCategory->id;
            foreach($children as $child){
                $grandChildren = $child['parent'];
                unset($child['parent']);
                $child['parent_id'] = $ParentID;
                $childCategory = Category::create($child);
                $ChildID = $childCategory->id;
                foreach($grandChildren as $grandChild){
                    $grandChild['parent_id'] = $ChildID;
                    Category::create($grandChild);
                }
            }
        }
    }
}
