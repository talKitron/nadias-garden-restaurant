<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 50)->create()->each(function ($user) {
        //     $user->posts()->save(factory(App\Post::class)->make());
        // });

        DB::table('users')->insert([
            // [
            //     'name' => Str::random(10),
            //     'email' => Str::random(10).'@gmail.com',
            //     'password' => Hash::make('password'),
            //     'is_admin' => 1,
            // ],
            [
                'name' => 'Test User',
                'email' => 'testuser@dev.nadias',
                'password' => Hash::make('1234567!'),
                'is_admin' => 1,
            ],
        ]);
    }
}