<?php

namespace Database\Factories;

use App\Models\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoinFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'wallet_id' => 1,
            'coin_id' => 'btc',
            'name' => 'user_name',
            'symbol' => 'coinSymbol',
            'amount' => 2,
            'value_usd' => 1
        ];
    }
}
