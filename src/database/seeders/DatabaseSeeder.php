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
        $user->wallet()->save(Wallet::factory()->create());

        $wallet = Wallet::query()->first();

        $coins = Coin::factory(Coin::class)->count(2)->create();

        foreach($coins as $coin){
            $wallet->coin()->attach($coin, ['amount' => 1, '']);
        }
    }
}
