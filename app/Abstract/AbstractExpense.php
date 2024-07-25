<?php

namespace App\Abstract;

use App\Interfaces\ExpenseInterface;

abstract class AbstractExpense implements ExpenseInterface
{
    /**
     * @return array
     */
    public function resolve(): array
    {
        return [
            'vehicle_id' => $this->getVehicleId(),
            'vehicle_name' => $this->getVehicleName(),
            'vehicle_plate_number' => $this->getVehiclePlateNumber(),
            'type' => $this->getType(),
            'cost' => $this->getCost(),
            'created_at' => $this->getCreationDate(),
        ];
    }
}
