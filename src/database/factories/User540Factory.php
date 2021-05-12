<?php

namespace Database\Factories;

use App\Models\User540;
use Illuminate\Database\Eloquent\Factories\Factory;

class User540Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => 1,
            'name' => 'user_name',
            'email' => 'email@email.com'
        ];
    }
}
