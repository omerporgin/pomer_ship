<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [

            'name' => '0.12 Karat Pırlanta Rose Kolye',
            'quantity' => $this->faker->randomDigitNot(0),
            'declared_quantity' => $this->faker->randomDigitNot(0),
            'unit_price' => $this->faker->randomDigitNot(0),
            'total_price' => $this->faker->randomDigitNot(0),
            'declared_price' => $this->faker->randomDigitNot(0),
            'total_custom_value' => $this->faker->randomDigitNot(0),
            'sku' => 'sku',
            'gtip_code' => 'gtip_code',
            'package_id' => $this->faker->randomDigitNot(0),
            'sort' => '2',
            'created_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'updated_at' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
        ];
    }
}
