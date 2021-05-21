<?php


namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id_transaction"=>1,
            "id_coin"=>'90',
            "usd_buyed_amount"=>50000,
            "buyed_coins_amount"=>1,
            "buyed_coins_usd_price"=>50000,
            "operation"=>'buy',
            "id_wallet"=>1
        ];
    }
}
