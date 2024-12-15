<?php

namespace database\seeders;

use App\Models\Label;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    protected array $pages = [
        'home' => [
            'name' => 'Home',
            'slug' => 'home',
            'visible' => true,
        ],
        'contact' => [
            'name' => 'Contact',
            'slug' => 'contact',
            'visible' => true,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->pages as $label => $attributes) {
            if (! Label::query()->where('label', $label)->exists()) {
                $page = Page::query()->create($attributes);

                $page->label()->create(['label' => $label]);
            }

        }
    }
}
