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

        // USERS
        DB::table('users')->insert([
            'user_id' => 'test-user',
        ]);

        DB::table('users')->insert([
            'user_id' => 'mkoding',
        ]);

        DB::table('users')->insert([
            'user_id' => 'bitoiz',
        ]);

        DB::table('users')->insert([
            'user_id' => 'idoate',
        ]);

        // WALLETS
        DB::table('wallets')->insert([
            'wallet_id' => 'test-wallet',
            'user_id' => 'test-user',
            'balance_usd' => 0,
        ]);

        // COINS
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

        DB::table('coins')->insert([
            'coin_id' => '2710',
            'name' => 'Binance Coin',
            'symbol' => 'BNB',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '58',
            'name' => 'XRP',
            'symbol' => 'XPR',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '257',
            'name' => 'Cardano',
            'symbol' => 'ADA',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '518',
            'name' => 'Tether',
            'symbol' => 'USDT',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '45219',
            'name' => 'Polkadot',
            'symbol' => 'DOT',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '2321',
            'name' => 'Bitcoin Cash',
            'symbol' => 'BCH',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '1',
            'name' => 'Litecoin',
            'symbol' => 'LTC',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '89',
            'name' => 'Stellar',
            'symbol' => 'XLM',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '2751',
            'name' => 'ChainLink',
            'symbol' => 'LINK',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '47305',
            'name' => 'Uniswap',
            'symbol' => 'UNI',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '118',
            'name' => 'Ethereum Classic',
            'symbol' => 'ETC',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '2741',
            'name' => 'VeChain',
            'symbol' => 'VET',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '32360',
            'name' => 'Theta Token',
            'symbol' => 'THETA',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '2679',
            'name' => 'EOS',
            'symbol' => 'EOS',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '2713',
            'name' => 'TRON',
            'symbol' => 'TRX',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '28',
            'name' => 'Monero',
            'symbol' => 'XMR',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '133',
            'name' => 'Neo',
            'symbol' => 'NEO',
        ]);

        DB::table('coins')->insert([
            'coin_id' => '46018',
            'name' => 'Aave',
            'symbol' => 'AAVE',
        ]);
    }
}
