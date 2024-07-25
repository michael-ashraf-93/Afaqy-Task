<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FuelEntry>
 */
class FuelEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'entry_date' => $this->faker->dateTimeThisYear('now', 'UTC')->format('Y-m-d h:i:s'),
            'volume' => $this->faker->randomFloat(2, 1, 100),
            'cost' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
