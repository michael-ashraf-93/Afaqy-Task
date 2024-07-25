<?php

namespace App\Interfaces;

use Carbon\Carbon;

interface ExpenseInterface
{
    /**
     * @return int
     */
    public function getVehicleId(): int;

    /**
     * @return string
     */
    public function getVehicleName(): string;

    /**
     * @return string
     */
    public function getVehiclePlateNumber(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return float
     */
    public function getCost(): float;

    /**
     * @return Carbon
     */
    public function getCreationDate(): Carbon;

    /**
     * @return array
     */
    public function resolve(): array;
}
