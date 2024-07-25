<?php

namespace Tests\Unit\Expenses;

use App\Enum\ExpensesEnums;
use App\Models\Vehicle;
use Tests\TestCase;

class ExpensesTest extends TestCase
{
    private string $api = '/api/expenses/vehicles/';

    private function getApi(int $vehicleId, ?array $query = null): string
    {
        $api = $this->api . $vehicleId;
        if (!empty($query)) {
            $api .= '?' . http_build_query($query);
        }
        return $api;
    }

    public function testValidInputs()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'type' => [ExpensesEnums::FuelEntry, ExpensesEnums::InsurancePayment],
            'min_cost' => 100,
            'max_cost' => 200,
            'min_creation_date' => '2024-01-01',
            'max_creation_date' => '2024-12-31',
            'sort_by' => 'cost',
            'sort_direction' => 'asc'
        ];
        $response = $this->get($this->getApi($vehicle->id, $query));
        $response->assertStatus(200);
    }

    public function testInvalidInputs()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'type' => ['InvalidType'],
            'min_cost' => 'abc',
            'max_cost' => 'xyz',
            'min_creation_date' => 'invalid-date',
            'max_creation_date' => 'not-a-date',
            'sort_by' => 'invalid-sort',
            'sort_direction' => 'invalid-direction',
        ];
        $response = $this->getJson($this->getApi($vehicle->id, $query));

        $response->assertStatus(422); // Assuming 422 Unprocessable Entity for validation errors
        $response->assertJsonValidationErrors(['type.0', 'min_cost', 'max_cost', 'min_creation_date', 'max_creation_date', 'sort_by', 'sort_direction']);
    }

    public function testEmptyArray()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'type' => [],
        ];
        $response = $this->get($this->getApi($vehicle->id, $query));
        $response->assertStatus(200);
    }

    public function testNullableValues()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'type' => null,
            'min_cost' => null,
            'max_cost' => null,
            'min_creation_date' => null,
            'max_creation_date' => null,
            'sort_by' => null,
            'sort_direction' => null
        ];
        $response = $this->get($this->getApi($vehicle->id, $query));

        $response->assertStatus(200);
    }

    public function testThrottling()
    {
        $vehicleA = Vehicle::query()->inRandomOrder()->first();
        $vehicleB = Vehicle::query()->inRandomOrder()->first();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->get($this->getApi($vehicleA->id));
            if ($i < 6) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429);
            }
        }

        for ($j = 1; $j <= 10; $j++) {
            $response = $this->get($this->getApi($vehicleB->id));
            if ($j < 6) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429);
            }
        }
    }
}
