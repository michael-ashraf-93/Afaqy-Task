<?php

namespace Tests\Unit\ExpenseAggregator;

use App\Enum\ExpensesEnums;
use App\Models\Vehicle;
use Tests\TestCase;

/**
 * @private string $api
 */
class ExpenseAggregatorListTest extends TestCase
{
    private string $api = '/api/expenses/aggregator';

    /**
     * Construct API
     *
     * @param array|null $query
     * @return string
     */
    private function getApi(?array $query = null): string
    {
        if (!empty($query)) {
            $this->api .= '?' . http_build_query($query);
        }
        return $this->api;
    }

    /**
     * Test valid inputs
     *
     * @return void
     */
    public function testValidInputs(): void
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

    /**
     * Test valid filter by vehicle_id
     *
     * @return void
     */
    public function testValidFilterById(): void
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'vehicle_id' => $vehicle->id,
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);

        $data = $response->json('data');
        foreach ($data as $item) {
            $this->assertStringContainsString($vehicle->id, $item['vehicle_id']);
        }
    }

    /**
     * Test valid filter by vehicle_name
     *
     * @return void
     */
    public function testValidFilterByName(): void
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'vehicle_name' => $vehicle->name,
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);

        $data = $response->json('data');
        foreach ($data as $item) {
            $this->assertStringContainsString($vehicle->name, $item['vehicle_name']);
        }
    }

    /**
     * Test valid filter by vehicle plate_number
     *
     * @return void
     */
    public function testValidFilterByPlateNumber(): void
    {
        $vehicle = Vehicle::query()->inRandomOrder()->first();
        $query = [
            'plate_number' => $vehicle->plate_number,
        ];
        $response = $this->get($this->getApi($query));
        $response->assertStatus(200);

        $data = $response->json('data');
        foreach ($data as $item) {
            $this->assertStringContainsString($vehicle->plate_number, $item['vehicle_plate_number']);
        }
    }

    /**
     * Test invalid inputs
     *
     * @return void
     */
    public function testInvalidInputs(): void
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

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type.0', 'min_cost', 'max_cost', 'min_creation_date', 'max_creation_date', 'sort_by', 'sort_direction']);
    }

    /**
     * Test empty type array
     *
     * @return void
     */
    public function testEmptyArray(): void
    {
        $query = [
            'type' => []
        ];
        $response = $this->get($this->getApi($query));

        $response->assertStatus(200);
    }

    /**
     * Test nullable values
     *
     * @return void
     */
    public function testNullableValues(): void
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

    /**
     * Test throttling
     *
     * @return void
     */
    public function testThrottling(): void
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
