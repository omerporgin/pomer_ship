<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'adress' => $this->faker->unique()->safeEmail(),
            'company_name' => $this->faker->company(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password()
        ];
    }
}
