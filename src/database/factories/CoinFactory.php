<?php


namespace Database\Factories;

use App\Models\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoinFactory
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
            'coin_id' => 90,
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'amount' => 0.63,
            'value_usd' => 75
        ];
    }

}
