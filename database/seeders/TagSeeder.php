<?php

namespace Database\Seeders;


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
        $topics = ['REPORTING', 'PROMOTION', 'USER', 'CATEGORY', 'PRODUCT', 'CART'];
        foreach( $topics as $key => $topic) {
            $oTopic = new Topic( ['name' => $topic]);
            $oTopic->save();
            Tag::factory()->count(10)->create( [ 'topic_id' => $oTopic->id] );
        }
    }
}
