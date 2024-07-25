<?php

namespace App\Services\Factories;

use App\Enum\ExpensesEnums;
use App\Interfaces\ExpenseInterface;
use App\Services\Factories\Expenses\FuelExpense;
use App\Services\Factories\Expenses\InsuranceExpense;
use App\Services\Factories\Expenses\ServiceExpense;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class ExpenseFactory
{
    /**
     * @param string $type
     * @param Model $entry
     * @return ExpenseInterface
     */
    public static function create(string $type, Model $entry): ExpenseInterface
    {
        return match ($type) {
            ExpensesEnums::FuelEntry => new FuelExpense($entry),
            ExpensesEnums::InsurancePayment => new InsuranceExpense($entry),
            ExpensesEnums::Service => new ServiceExpense($entry),
            default => throw new InvalidArgumentException("Unknown expense type"),
        };
    }
}
