<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [];
        $category = [ 'english' => 'resistors', 'spanish' => 'resistencias', 'tag' => 'resistors', 'active' => 'YES',
            'parent' => [
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
        foreach($categories as $category){
            Category::create($category);
        }
    }
}
