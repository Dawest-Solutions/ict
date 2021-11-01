<?php

namespace Database\Factories;

use App\Models\RewardInDay;
use Illuminate\Database\Eloquent\Factories\Factory;

class RewardInDayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RewardInDay::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeBetween('-1 days', '+10 days'),
            'value' => rand(300,1000),
        ];
    }
}
