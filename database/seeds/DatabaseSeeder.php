<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call([
            PostSeeder::class,
            EmailSeeder::class,
            FeedSeeder::class,
            CoverSeeder::class,
            UserSeeder::class,
        ]);
    }
}
