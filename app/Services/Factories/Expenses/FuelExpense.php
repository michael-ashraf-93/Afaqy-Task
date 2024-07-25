<?php

namespace App\Services\Factories\Expenses;

use App\Abstract\AbstractExpense;
use App\Enum\ExpensesEnums;
use App\Interfaces\ExpenseInterface;
use Carbon\Carbon;

/**
 * @private mixed $fuelEntry
 */
class FuelExpense extends AbstractExpense implements ExpenseInterface
{
    private mixed $fuelEntry;

    /**
     * @param mixed $fuelEntry
     */
    public function __construct(mixed $fuelEntry)
    {
        $this->fuelEntry = $fuelEntry;
    }

    /**
     * @return int
     */
    public function getVehicleId(): int
    {
        return $this->fuelEntry->vehicle->id;
    }

    /**
     * @return string
     */
    public function getVehicleName(): string
    {
        return $this->fuelEntry->vehicle->name;
    }

    /**
     * @return string
     */
    public function getVehiclePlateNumber(): string
    {
        return $this->fuelEntry->vehicle->plate_number;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ExpensesEnums::FuelEntry;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->fuelEntry->cost;
    }

    /**
     * @return Carbon
     */
    public function getCreationDate(): Carbon
    {
        return Carbon::parse($this->fuelEntry->created_at);
    }
}
