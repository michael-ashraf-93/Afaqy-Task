<?php

namespace App\Services;

use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Models\Vehicle;
use App\Repositories\VehicleExpensesRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @private VehicleExpensesRepository $vehicleExpensesRepository
 */
class VehicleExpensesService
{
    /**
     * @var VehicleExpensesRepository
     */
    private VehicleExpensesRepository $vehicleExpensesRepository;

    /**
     * @param VehicleExpensesRepository $vehicleExpensesRepository
     */
    public function __construct(VehicleExpensesRepository $vehicleExpensesRepository)
    {
        $this->vehicleExpensesRepository = $vehicleExpensesRepository;
    }

    /**
     * @param ExpensesListRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpenses(ExpensesListRequest $request): LengthAwarePaginator
    {
        return $this->vehicleExpensesRepository->getExpenses(request: $request);
    }

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpensesByVehicle(Vehicle $vehicle, VehicleExpensesRequest $request): LengthAwarePaginator
    {
        return $this->vehicleExpensesRepository->getExpensesByVehicle(vehicle: $vehicle, request: $request);
    }
}
