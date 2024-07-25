<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'plate_number' => $this->faker->randomDigit(),
            'imei' => $this->faker->randomDigit(),
            'vin' => $this->faker->randomDigit(),
            'year' => Carbon::now()->format('Y'),
            'license' => $this->faker->randomDigit(),
        ];
    }
}
