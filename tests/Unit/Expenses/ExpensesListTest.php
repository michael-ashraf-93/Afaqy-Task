<?php

namespace Tests\Unit\Expenses;

use App\Enum\ExpensesEnums;
use App\Models\Vehicle;
use Tests\TestCase;

class ExpensesListTest extends TestCase
{
    private string $api = '/api/expenses/';

    private function getApi(?array $query = null): string
    {
        if (!empty($query)) {
            $this->api .= '?' . http_build_query($query);
        }
        return $this->api;
    }

    public function testValidInputs()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'vehicle_id' => $vehicle->id,
            'vehicle_name' => $vehicle->name,
            'plate_number' => $vehicle->plate_number,
            'type' => [ExpensesEnums::FuelEntry, ExpensesEnums::InsurancePayment],
            'min_cost' => 100,
            'max_cost' => 200,
            'min_creation_date' => '2024-01-01',
            'max_creation_date' => '2024-12-31',
            'sort_by' => 'cost',
            'sort_direction' => 'asc',
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);
    }

    public function testValidFilterById()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'id' => $vehicle->id,
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);
    }

    public function testValidFilterByName()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'vehicle' => $vehicle->name,
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);
    }

    public function testValidFilterByPlateNumber()
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'vehicle' => $vehicle->plate_number,
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);
    }

    public function testInvalidInputs()
    {
        $query = [
            'type' => ['InvalidType'],
            'min_cost' => 'abc',
            'max_cost' => 'xyz',
            'min_creation_date' => 'invalid-date',
            'max_creation_date' => 'not-a-date',
            'sort_by' => 'invalid-sort',
            'sort_direction' => 'invalid-direction',
        ];
        $response = $this->getJson($this->getApi($query));

        $response->assertStatus(422); // Assuming 422 Unprocessable Entity for validation errors
        $response->assertJsonValidationErrors(['type.0', 'min_cost', 'max_cost', 'min_creation_date', 'max_creation_date', 'sort_by', 'sort_direction']);
    }

    public function testEmptyArray()
    {
        $query = [
            'type' => []
        ];
        $response = $this->get($this->getApi($query));

        $response->assertStatus(200);
    }

    public function testNullableValues()
    {
        $query = [
            'type' => null,
            'min_cost' => null,
            'max_cost' => null,
            'min_creation_date' => null,
            'max_creation_date' => null,
            'sort_by' => null,
            'sort_direction' => null,
        ];
        $response = $this->get($this->getApi($query));

        $response->assertStatus(200);
    }

    public function testThrottling()
    {
        for ($i = 1; $i <= 10; $i++) {
            $response = $this->get($this->getApi());
            if ($i < 6) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429);
            }
        }
    }
}
