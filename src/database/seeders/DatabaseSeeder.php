<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            'user_id' => 'test-user',
        ]);

        DB::table('wallets')->insert([
            'wallet_id' => 'test-wallet',
            'user_id' => 'test-user',
            'balance_usd' => 0,
        ]);

        DB::table('coins')->insert([
            'coin_id' => '90',
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '80',
            'name' => 'Ethereum',
            'symbol' => 'ETH',
        ]);

        DB::table('walletscoins')->insert([
            'wallet_id' => 'test-wallet',
            'coin_id' => '90',
            'amount' => 0,
        ]);
    }
}
