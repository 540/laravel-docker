<?php


namespace Database\Factories;


use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition()
    {
        return [
            'id' => 1,
            'id_user' => 1
        ];
    }
}
