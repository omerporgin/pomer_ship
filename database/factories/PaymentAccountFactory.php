<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentAccount>
 */
class PaymentAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'payment_id' => '2',
            'installment' => $this->faker->numberBetween($min = 1, $max = 12),
            'status' => $this->faker->numberBetween($min = 0, $max =1),
            'total' => $this->faker->numberBetween($min = 100, $max = 10000),
            'created_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'updated_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
        ];
    }
}
