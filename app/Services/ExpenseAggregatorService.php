<?php

namespace App\Services;

use App\Enum\ExpensesEnums;
use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Models\Vehicle;
use App\Repositories\FuelEntryRepository;
use App\Repositories\InsurancePaymentRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * @private FuelEntryRepository $fuelEntryRepository
 * @private InsurancePaymentRepository $insurancePaymentRepository
 * @private ServiceRepository $serviceRepository
 */
class ExpenseAggregatorService
{

    private FuelEntryRepository $fuelEntryRepository;
    private InsurancePaymentRepository $insurancePaymentRepository;
    private ServiceRepository $serviceRepository;

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
        $this->fuelEntryRepository = $fuelEntryRepository;
        $this->insurancePaymentRepository = $insurancePaymentRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return Collection
     */
    public function getExpensesByVehicle(Vehicle $vehicle, VehicleExpensesRequest $request): Collection
    {
        $fuelExpenses = $this->fuelEntryRepository->getExpensesByVehicle(vehicle: $vehicle, request: $request);
        $insuranceExpenses = $this->insurancePaymentRepository->getExpensesByVehicle(vehicle: $vehicle, request: $request);
        $servicesExpenses = $this->serviceRepository->getExpensesByVehicle(vehicle: $vehicle, request: $request);

        $expenses = $fuelExpenses->unionAll($insuranceExpenses)->unionAll($servicesExpenses);

        $sortBy = $request['sort_by'] ?? 'created_at';
        $sortDirection = $request['sort_direction'] ?? 'desc';

        $expenses = $expenses->orderBy($sortBy, $sortDirection);

        $expenses = $expenses->get();

        if ($request->validated('min_cost')) {
            $expenses = $expenses->where('cost', '>=', $request->validated('min_cost'));
        }

        if ($request->validated('max_cost')) {
            $expenses = $expenses->where('cost', '<=', $request->validated('max_cost'));
        }

        if ($request->validated('min_creation_date')) {
            $expenses = $expenses->where('created_at', '>=', $request->validated('min_creation_date'));
        }

        if ($request->validated('max_creation_date')) {
            $expenses = $expenses->where('created_at', '<=', $request->validated('max_creation_date'));
        }

        if (isset($request['type'])) {
            $expenses = $expenses->whereIn('type', $request['type'])->values();
        }
        return $expenses;
    }

    /**
     * @param ExpensesListRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpenses(ExpensesListRequest $request): LengthAwarePaginator
    {
        $fuelQuery = DB::table('fuel_entries')
            ->select(
                'vehicle_id',
                DB::raw('cost as cost'),
                DB::raw('entry_date as created_at'),
                DB::raw("'" . ExpensesEnums::FuelEntry . "' as type")
            );

        $insuranceQuery = DB::table('insurance_payments')
            ->select(
                'vehicle_id',
                DB::raw('amount as cost'),
                DB::raw('contract_date as created_at'),
                DB::raw("'" . ExpensesEnums::InsurancePayment . "' as type")
            );

        $serviceQuery = DB::table('services')
            ->select(
                'vehicle_id',
                DB::raw('total as cost'),
                DB::raw('created_at as created_at'),
                DB::raw("'" . ExpensesEnums::Service . "' as type")
            );

        $query = $fuelQuery->union($insuranceQuery)->union($serviceQuery);
        $expenses = DB::table('vehicles')
            ->leftJoinSub($query, 'expenses', 'vehicles.id', '=', 'expenses.vehicle_id')
            ->select(
                'vehicles.id as vehicle_id',
                'vehicles.name as vehicle_name',
                'vehicles.plate_number',
                'type',
                'cost',
                'expenses.created_at',
            );
        $expenses = $this->filter(expenses: $expenses, request: $request);
        $expenses = $this->sort(expenses: $expenses, request: $request);
        return $this->paginate(expenses: $expenses, request: $request);
    }


    /**
     * @param $expenses
     * @param ExpensesListRequest $request
     * @return Builder
     */
    private function filter($expenses, ExpensesListRequest $request): Builder
    {
        if ($request->validated('type')) {
            $expenses->whereIn('type', $request->validated('type'));
        }

        if ($request->validated('vehicle_id')) {
            $expenses->where('vehicle_id', $request->validated('vehicle_id'));
        }

        if ($request->validated('vehicle_name')) {
            $expenses->where('name', 'LIKE', '%' . $request->validated('vehicle_name') . '%');
        }

        if ($request->validated('plate_number')) {
            $expenses->where('plate_number', 'LIKE', '%' . $request->validated('plate_number') . '%');
        }

        if ($request->validated('min_cost')) {
            $expenses->havingRaw('cost >= ' . $request->validated('min_cost'));
        }

        if ($request->validated('max_cost')) {
            $expenses->havingRaw('cost <= ' . $request->validated('max_cost'));
        }

        if ($request->validated('min_creation_date')) {
            $expenses->where('expenses.created_at', '>=', $request->validated('min_creation_date'));
        }

        if ($request->validated('max_creation_date')) {
            $expenses->where('expenses.created_at', '<=', $request->validated('max_creation_date'));
        }
        return $expenses;
    }

    /**
     * @param $expenses
     * @param ExpensesListRequest $request
     * @return Builder
     */
    private function sort($expenses, ExpensesListRequest $request): Builder
    {
        $sortBy = $request->validated('sort_by') ?? 'created_at';
        $sortDirection = $request->validated('sort_direction') ?? 'desc';
        $expenses->orderBy($sortBy, $sortDirection);
        return $expenses;
    }

    /**
     * @param $expenses
     * @param $request
     * @return LengthAwarePaginator
     */
    private function paginate($expenses, $request): LengthAwarePaginator
    {
        $defaultPerPage = 100;
        $perPage = $request->per_page ?? $defaultPerPage;
        $perPage = min($perPage, $defaultPerPage);

        return $expenses->paginate($perPage);
    }
}
