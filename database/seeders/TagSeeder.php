<?php

namespace Database\Seeders;


use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = ['REPORTING', 'PROMOTION', 'USER', 'CATEGORY', 'PRODUCT', 'CART', 'LANDING'];
        $tags = ['NEW','BASEMENT',"FAST-MOVING","PROMOTED"];
        foreach( $topics as $key => $topic) {
            $oTopic = new Topic( ['name' => $topic]);
            $oTopic->save();
            if($topic === 'LANDING') {
                foreach($tags as $tag) {
                    $tag = new Tag(['name' => $tag, 'topic_id' => $oTopic->id, 'visible' => 'YES']);
                    $tag->save();
                }
            }else{
                Tag::factory()->count(5)->create( [ 'topic_id' => $oTopic->id] );
            }
        }
        $tags = Tag::whereIn('name', $tags)->get();
        foreach( $tags as $tag) {
            $products = Product::doesntHave('tags')
                ->inRandomOrder()
                ->limit(5)
                ->get();
            foreach( $products as $product) {
                $productTag = new ProductTag( [ 'product_id' => $product->id, 'tag_id' => $tag->id]);
                $productTag->save();
            }
        }
    }
}
