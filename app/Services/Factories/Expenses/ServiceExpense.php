<?php

namespace App\Services\Factories\Expenses;

use App\Abstract\AbstractExpense;
use App\Enum\ExpensesEnums;
use App\Interfaces\ExpenseInterface;
use Carbon\Carbon;

/**
 * @private mixed $service
 */
class ServiceExpense extends AbstractExpense implements ExpenseInterface
{
    private mixed $service;

    /**
     * @param mixed $service
     */
    public function __construct(mixed $service)
    {
        $this->service = $service;
    }

    /**
     * @return int
     */
    public function getVehicleId(): int
    {
        return $this->service->vehicle_id;
    }

    /**
     * @return string
     */
    public function getVehicleName(): string
    {
        return $this->service->vehicle->name;
    }

    /**
     * @return string
     */
    public function getVehiclePlateNumber(): string
    {
        return $this->service->vehicle->plate_number;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ExpensesEnums::Service;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->service->cost;
    }

    /**
     * @return Carbon
     */
    public function getCreationDate(): Carbon
    {
        return Carbon::parse($this->service->created_at);
    }
}
