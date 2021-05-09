<?php

namespace Database\Factories;

use App\Models\WalletCoin;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletCoinFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WalletCoin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'wallet_id' => 1,
            'coin_id' => 1,
            'amount' => 1,
            'value_usd' => 1
        ];
    }
}
