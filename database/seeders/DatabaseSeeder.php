<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\SubPage;
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
        SubPage::factory()->create();
        Post::factory(5)->create(["sub_page_id" => 1]);
    }
}
