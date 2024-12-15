<?php

namespace Database\Seeders;

use App\Enums\MenuEnum;
use App\Models\Label;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(MenuEnum::getValues() as $menu) {
            $menuLabel = Label::getModel($menu);

            if(!$menuLabel) {
                $mainMenu = Menu::query()
                    ->create([
                        'title' => $menu,
                    ]);

                $mainMenu->label()->create([
                    'label' => $menu
                ]);
            }
        }

    }
}
