<?php

namespace App\Services;

use App\Enum\ExpensesEnums;
use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Models\Vehicle;
use App\Repositories\FuelEntryRepository;
use App\Repositories\InsurancePaymentRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @private FuelEntryRepository $fuelEntryRepository
 * @private InsurancePaymentRepository $insurancePaymentRepository
 * @private ServiceRepository $serviceRepository
 * @private array $expensesTypes
 */
class ExpenseService
{
    private FuelEntryRepository $fuelEntryRepository;
    private InsurancePaymentRepository $insurancePaymentRepository;
    private ServiceRepository $serviceRepository;
    private array $expensesTypes;

    /**
     * @param FuelEntryRepository $fuelEntryRepository
     * @param InsurancePaymentRepository $insurancePaymentRepository
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(
        FuelEntryRepository        $fuelEntryRepository,
        InsurancePaymentRepository $insurancePaymentRepository,
        ServiceRepository          $serviceRepository
    )
    {
        $this->expensesTypes = [
            ExpensesEnums::FuelEntry => $this->fuelEntryRepository = $fuelEntryRepository,
            ExpensesEnums::InsurancePayment => $this->insurancePaymentRepository = $insurancePaymentRepository,
            ExpensesEnums::Service => $this->serviceRepository = $serviceRepository,
        ];
    }

    /**
     * @param ExpensesListRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpenses(ExpensesListRequest $request): LengthAwarePaginator
    {
        $filteredTypes = $request->validated('type') ?? array_keys($this->expensesTypes);

        $query = null;
        foreach ($filteredTypes as $type) {
            $repository = $this->expensesTypes[$type];
            if (null === $query) {
                $query = $repository->getExpenses(request: $request);
            } else {
                $query->union($repository->getExpenses(request: $request));
            }
        }

        $query = $this->sort(query: $query, request: $request);
        return $this->paginate(query: $query, request: $request);
    }

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpensesByVehicle(Vehicle $vehicle, VehicleExpensesRequest $request): LengthAwarePaginator
    {
        $filteredTypes = $request->validated('type') ?? array_keys($this->expensesTypes);

        $query = null;
        foreach ($filteredTypes as $type) {
            $repository = $this->expensesTypes[$type];
            if (null === $query) {
                $query = $repository->getExpensesByVehicle(vehicle: $vehicle, request: $request);
            } else {
                $query->unionAll($repository->getExpensesByVehicle(vehicle: $vehicle, request: $request));
            }
        }

        $query = $this->sort(query: $query, request: $request);

        return $this->paginate(query: $query, request: $request);
    }

    /**
     * @param Builder|HasMany $query
     * @param VehicleExpensesRequest|ExpensesListRequest $request
     * @return Builder|HasMany
     */
    private function sort(Builder|HasMany $query, VehicleExpensesRequest|ExpensesListRequest $request): Builder|HasMany
    {
        $sortBy = $request->validated('sort_by') ?? 'created_at';
        $sortDirection = $request->validated('sort_direction') ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);
        return $query;
    }

    /**
     * @param Builder|HasMany $query
     * @param VehicleExpensesRequest|ExpensesListRequest $request
     * @return LengthAwarePaginator
     */
    private function paginate(Builder|HasMany $query, VehicleExpensesRequest|ExpensesListRequest $request): LengthAwarePaginator
    {
        $perPage = min($request->input('per_page', 100), 100);
        return $query->paginate($perPage);
    }
}
