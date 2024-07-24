<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $seeds = [];
        switch(\App::Environment()) {

            /**
             * Local/testing seeds
             */
            case 'local':
            case 'staging':
            case 'production':
            case 'testing':
                $seeds = [
                    PermissionsSeeder::class,
                    UsersSeeder::class,
                    ProductsSeeder::class,
                ];
                break;
        }
        /**
         * Run seeders
         */
        array_map(fn($s) => $this->call($s), $seeds);
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
