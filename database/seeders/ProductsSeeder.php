<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $categories = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first()->toArray();
            $products = Product::factory()->count(20)->create();
            if( empty( $categories['children'])) {
                foreach ($products as $product) {
                    $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => $this->randomFloat(), 'quantity' => 0, 'discount' => 0]);
                    $productPrice->save();
                    for( $discount = 10; $discount < 50; $discount += 10 ) {
                        $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => 0, 'quantity' => $discount, 'discount' => $discount]);
                        $productPrice->save();
                    }
                    $productCategory = new ProductCategory([
                        'id' => $categories['id'],
                        'parent_id' => $categories['parent_id'],
                        'product_id' => $product['id']
                    ]);
                    $productCategory->save();
                }
            }
            foreach($categories['children'] as $child){
                $products = Product::factory()->count(20)->create();
                if( empty( $child['children'])) {
                    foreach ($products as $product) {
                        $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => $this->randomFloat(), 'quantity' => 0, 'discount' => 0]);
                        $productPrice->save();
                        for( $discount = 10; $discount < 50; $discount += 10 ) {
                            $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => 0, 'quantity' => $discount, 'discount' => $discount]);
                            $productPrice->save();
                        }
                        $productCategory = new ProductCategory([
                            'id' => $child['id'],
                            'parent_id' => $child['parent_id'],
                            'product_id' => $product['id']
                        ]);
                        $productCategory->save();
                    }
                }
                foreach($child['children'] as $grandChild){
                    $products = Product::factory()->count(20)->create();
                    if( empty( $grandChild['children'])) {
                        foreach ($products as $product) {
                            $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => $this->randomFloat(), 'quantity' => 0, 'discount' => 0]);
                            $productPrice->save();
                            for( $discount = 10; $discount < 50; $discount += 10 ) {
                                $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => 0, 'quantity' => $discount, 'discount' => $discount]);
                                $productPrice->save();
                            }
                            $productCategory = new ProductCategory([
                                'id' => $grandChild['id'],
                                'parent_id' => $grandChild['parent_id'],
                                'product_id' => $product['id']
                            ]);
                            $productCategory->save();
                        }
                    }
                    foreach($grandChild['children'] as $grandGrandChild){
                        $products = Product::factory()->count(20)->create();
                        if( empty( $grandGrandChild['children'])) {
                            foreach($products as $product){
                                $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => $this->randomFloat(), 'quantity' => 0, 'discount' => 0]);
                                $productPrice->save();
                                for( $discount = 10; $discount < 50; $discount += 10 ) {
                                    $productPrice = new ProductPrice(['product_id' => $product->id, 'price' => 0, 'quantity' => $discount, 'discount' => $discount]);
                                    $productPrice->save();
                                }
                                $productCategory = new ProductCategory([
                                    'id' => $grandGrandChild['id'],
                                    'parent_id' => $grandGrandChild['parent_id'],
                                    'product_id' => $product['id']]);
                                $productCategory->save();
                            }
                        }
                    }
                }
            }
        });
    }
    private function randomFloat($min = 0, $max = 100, $decimals = 2){
        $divisor = pow(10, $decimals);
        $randomFloat = mt_rand($min, $max * $divisor) / $divisor;
        return $randomFloat;
    }
}
