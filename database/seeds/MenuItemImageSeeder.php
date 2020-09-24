<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_item_id = 1;
        DB::table('menu_item_images')->insert([
            [
                'image' => 'dummy/mini_cheeseburgers.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/french_onion_soup.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/artichokes_with_garlic_aioli.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/parmesan_deviled_eggs.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/parmesan_deviled_eggs.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/house_salad.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/chefs_salad.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/quinoa_salmon_salad.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/classic_burger.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/tomato_bruschetta_tortellini.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/handcrafted_pizza.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/barbecued_tofu_skewers.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/fiesta_family_platter.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/creme_brulee.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/cheesecake.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/chocolate_chip_brownie.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/apple_pie.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => 'dummy/mixed_berry_tart.jpg',
                'user_id' => 1,
                'menu_item_id' => $menu_item_id++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
