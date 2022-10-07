<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => $this->faker->numberBetween($min = 13, $max = 14) ,
            'real_status' => $this->faker->numberBetween($min = 13, $max = 14) ,
            'vendor_id' => $this->faker->randomDigitNot(0),
            'entegration_id' => $this->faker->randomDigitNot(0),
            'order_id' => $this->faker->randomDigitNot(0),
            'currency' => '119',
            'total_price' => $this->faker->randomNumber(2),
            'declared_price' => $this->faker->randomNumber(2),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'country_id' => $this->faker->randomDigitNot(0),
            'state_id' => $this->faker->randomDigitNot(0),
            'post_code' => $this->faker->postcode(),
            'data' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'order_date' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'created_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'updated_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
        ];
    }
}
