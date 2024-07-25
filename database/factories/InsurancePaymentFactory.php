<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InsurancePayment>
 */
class InsurancePaymentFactory extends Factory
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
            'contract_date' => $this->faker->dateTimeThisYear('now', 'UTC')->format('Y-m-d h:i:s'),
            'expiration_date' => $this->faker->dateTimeThisYear('now', 'UTC')->format('Y-m-d h:i:s'),
            'amount' => $this->faker->randomFloat(2, 10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
