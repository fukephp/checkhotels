<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'date' => $this->faker->date($format = 'Y-m-d', $max = 'now')
        ];
    }
}
