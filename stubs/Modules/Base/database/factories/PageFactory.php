<?php

declare(strict_types=1);

namespace database\factories;

use App\Models\Page;
use Database\Factories\Helpers\FactoryImage;
use Database\Factories\Helpers\WithBlocks;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    use WithBlocks;

    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'name' => $name = fake()->words(3, true),
            'slug' => Str::slug($name),
            'content' => fake()->paragraph,
            'image_id' => FactoryImage::make()->label($name)->cropperField(800, 800),
            'visible' => true,
        ];
    }
}
