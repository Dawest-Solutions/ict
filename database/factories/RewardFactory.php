<?php

namespace Database\Factories;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reward::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'name' => $this->faker->sentence(rand(5,10)),
          'name_chance' => $this->faker->sentence(rand(1,3)),
          'service' => $this->faker->sentence(1),
          'image_path' => '',
          'location' => "{$this->faker->city()}, {$this->faker->streetAddress()}",
          'value' => rand(1,100),
          'description' => $this->faker->realText(),
        ];
    }
}
