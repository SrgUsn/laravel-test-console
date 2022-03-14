<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'surname' =>  $this->faker->lastName(),
            'email' => $this->faker->unique()->email(),
            'age' => $this->faker->numberBetween(1, 100),
            'location' => $this->faker->address(),
            'country_code' => $this->faker->stateAbbr(),
        ];
    }
}


