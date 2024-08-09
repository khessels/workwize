<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    // https://blog.ghanshyamdigital.com/building-a-self-referencing-model-in-laravel-a-step-by-step-guide
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $root = [ 'label' => 'root', 'data' => 'root of categories tree', 'icon' => 'pi pi-fw pi-inbox', 'active' => 'YES', 'children' =>
            [[ 'label' => 'resistors', 'icon' => 'pi pi-fw pi-home', 'active' => 'YES', 'children' => [
                [ 'label' => 'through hole', 'active' => 'YES', 'children' => [
                    [ 'label' => '1/8 Watt', 'active' => 'YES'],
                    [ 'label' => '1/4 Watt', 'active' => 'YES'],
                ]],
                [ 'label' => 'SMD', 'icon' => 'pi pi-fw pi-calendar', 'active' => 'YES', 'children' => [
                    [ 'label' => '1206', 'active' => 'YES'],
                    [ 'label' => '604', 'active' => 'YES'],
                ]],
            ]]
            ]
        ];
        $roots[] = $root;

        DB::Transaction(function () use ($roots) {
            foreach($roots as $root){
                if( ! empty($root['children'])){
                    $children = $root['children'];
                    unset($root['children']);
                }
                $parentCategory = Category::create($root);
                $parentCategory->save();
                $ParentID = $parentCategory->id;
                if( ! empty($children)){
                    foreach($children as $child){
                        if( ! empty($child['children'])) {
                            $grandChildren = $child['children'];
                            unset($child['children']);
                        }
                        $child['parent_id'] = $ParentID;
                        $childCategory = Category::create($child);
                        $ChildID = $childCategory->id;
                        if( ! empty( $grandChildren)){
                            foreach($grandChildren as $grandChild){
                                if( ! empty($grandChild['children'])) {
                                    $grandGrandChildren = $grandChild['children'];
                                    unset($grandChild['children']);
                                }
                                $grandChild['parent_id'] = $ChildID;
                                $grandChild = Category::create($grandChild);
                                $grandChildID = $grandChild->id;
                                if( ! empty( $grandGrandChildren)) {
                                    foreach ($grandGrandChildren as $grandGrandChild) {
                                        if (!empty($grandGrandChild['children'])) {
                                            $grandGrandGrandChildren = $grandGrandChild['children'];
                                            unset($grandGrandChild['children']);
                                        }
                                        $grandGrandChild['parent_id'] = $grandChildID;
                                        $grandGrandChild = Category::create($grandGrandChild);
                                        $grandGrandChildID = $grandGrandChild->id;
                                        if (!empty($grandGrandGrandChildren)) {
                                            foreach ($grandGrandGrandChildren as $grandGrandGrandChild) {
                                                $grandGrandGrandChild['parent_id'] = $grandGrandChildID;
                                                Category::create($grandGrandGrandChild);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    }
}
