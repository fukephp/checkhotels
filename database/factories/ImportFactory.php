<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'placesdata1.csv',
            'path' => 'csv/placesdata1.csv'
        ];
    }
}
