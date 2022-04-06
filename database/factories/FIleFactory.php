<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FIleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,10),
            'name' => $this->faker->name(),
            'file_size'=>$this->faker->numberBetween(1,10000),
        ];
    }
}
