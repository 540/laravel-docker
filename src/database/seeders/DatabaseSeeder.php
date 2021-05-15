<?php

namespace Database\Seeders;

use App\Models\Coin;
use App\Models\Wallet;
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
        Wallet::factory(1)->create();
        Coin::factory(10)->create();
    }
}
