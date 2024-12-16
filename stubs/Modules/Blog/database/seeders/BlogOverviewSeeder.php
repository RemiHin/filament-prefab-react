<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogOverviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogOverview = Label::getModel('blog-overview');

        if(!$blogOverview) {
            $blogPage = Page::query()
                ->create([
                'name' => 'blogs',
                'slug' => 'blogs',
                'visible' => true,
            ]);

            $blogPage->label()->create([
                'label' => 'blog-overview'
            ]);
        }
    }
}
