<?php

namespace Database\Seeders;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use Faker\Factory;
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
        $user = User::factory()->create();
        $user->wallet()->save(Wallet::factory()->make());

        $wallet = Wallet::query()->first();

        $coins = Coin::factory(Coin::class)->count(2)->make();

        $wallet->coins()->saveMany($coins);
    }
}
