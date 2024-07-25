<?php

namespace App\Repositories;

use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Models\Vehicle;
use App\Models\Views\VehicleExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleExpensesRepository
{
    /**
     * @param ExpensesListRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpenses(ExpensesListRequest $request): LengthAwarePaginator
    {
        $query = VehicleExpense::query();
        $query = $this->filter(query: $query, request: $request);
        $query = $this->sort($query, request: $request);
        return $this->paginate($query, request: $request);
    }

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return LengthAwarePaginator
     */
    public function getExpensesByVehicle(Vehicle $vehicle, VehicleExpensesRequest $request): LengthAwarePaginator
    {
        $query = $vehicle->expenses();
        $query = $this->filter(query: $query, request: $request);
        $query = $this->sort(query: $query, request: $request);
        return $this->paginate(query: $query, request: $request);
    }

    /**
     * @param Builder|HasMany $query
     * @param VehicleExpensesRequest|ExpensesListRequest $request
     * @return Builder|HasMany
     */
    private function filter(Builder|HasMany $query, VehicleExpensesRequest|ExpensesListRequest $request): Builder|HasMany
    {
        if ($request->validated('type')) {
            $query->whereIn('type', $request->validated('type'));
        }

        if ($request->validated('vehicle_id')) {
            $query->where('vehicle_id', $request->validated('vehicle_id'));
        }

        if ($request->validated('vehicle_name')) {
            $query->where('vehicle_name', 'like', '%' . $request->validated('vehicle_name') . '%');
        }

        if ($request->validated('plate_number')) {
            $query->where('vehicle_plate_number', 'like', '%' . $request->validated('plate_number') . '%');
        }

        if ($request->validated('min_cost')) {
            $query->where('cost', '>=', $request->validated('min_cost'));
        }

        if ($request->validated('max_cost')) {
            $query->where('cost', '<=', $request->validated('max_cost'));
        }

        if ($request->validated('min_creation_date')) {
            $query->where('created_at', '>=', $request->validated('min_creation_date'));
        }

        if ($request->validated('max_creation_date')) {
            $query->where('created_at', '<=', $request->validated('max_creation_date'));
        }
        return $query;
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
        return $query->orderBy($sortBy, $sortDirection);
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
