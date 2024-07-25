<?php

namespace Database\Factories;

use App\Enum\ServiceStatusEnums;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        $vehicle = Vehicle::query()->first() ?? Vehicle::factory()->create();
//        $startDate = Carbon::parse($this->faker->date());
//        $endDate = $startDate->addYear();
        return [
            'vehicle_id' => Vehicle::factory(),
//            'start_date' => $startDate->toDateString(),
//            'end_date' => $endDate->toDateString(),
            'start_date' => $this->faker->dateTimeThisYear('now', 'UTC')->format('Y-m-d h:i:s'),
            'end_date' => $this->faker->dateTimeThisYear('now', 'UTC')->format('Y-m-d h:i:s'),
            'invoice_number' => $this->faker->randomNumber(),
            'purchase_order_number' => $this->faker->randomNumber(),
            'status' => ServiceStatusEnums::STATUS_OPEN,
            'discount' => $this->faker->randomNumber(),
            'tax' => $this->faker->randomNumber(),
            'total' => $this->faker->randomNumber(),
        ];
    }
}
