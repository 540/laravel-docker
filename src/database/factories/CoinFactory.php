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
            'coin_id' => rand(7, 9) * 10,
            'name' => $this->faker->word,
            'symbol' => $this->faker->currencyCode,
            'amount' => 1,
            'value_usd' => 1
        ];
    }
}
