<?php

namespace App\Services\Factories\Expenses;

use App\Abstract\AbstractExpense;
use App\Enum\ExpensesEnums;
use App\Interfaces\ExpenseInterface;
use Carbon\Carbon;

/**
 * @private mixed $insurancePayment
 */
class InsuranceExpense extends AbstractExpense implements ExpenseInterface
{
    private mixed $insurancePayment;

    /**
     * @param mixed $insurancePayment
     */
    public function __construct(mixed $insurancePayment)
    {
        $this->insurancePayment = $insurancePayment;
    }

    /**
     * @return int
     */
    public function getVehicleId(): int
    {
        return $this->insurancePayment->vehicle_id;
    }

    /**
     * @return string
     */
    public function getVehicleName(): string
    {
        return $this->insurancePayment->vehicle->name;
    }

    /**
     * @return string
     */
    public function getVehiclePlateNumber(): string
    {
        return $this->insurancePayment->vehicle->plate_number;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ExpensesEnums::InsurancePayment;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->insurancePayment->cost;
    }

    /**
     * @return Carbon
     */
    public function getCreationDate(): Carbon
    {
        return Carbon::parse($this->insurancePayment->created_at);
    }
}
