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
            'api_destination_id' => '12547341',
            'api_geo_id' => '553248635939577681',
            'date' => $this->faker->date($format = 'Y-m-d', $max = 'now')
        ];
    }
}
