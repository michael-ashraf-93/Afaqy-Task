<?php

namespace App\Repositories;

use App\Enum\ExpensesEnums;
use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Models\Service;
use App\Models\Vehicle;
use App\Traits\FilterByVehicleTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ServiceRepository
{
    use FilterByVehicleTrait;

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return Builder|HasMany
     */
    public function getExpensesByVehicle(Vehicle $vehicle, VehicleExpensesRequest $request): Builder|HasMany
    {
        $query = $vehicle->services();
        return $this->filter(query: $query, request: $request);
    }

    /**
     * @param ExpensesListRequest $request
     * @return Builder
     */
    public function getExpenses(ExpensesListRequest $request): Builder
    {
        $query = Service::query();
        $query = $this->filter(query: $query, request: $request);
        return $this->filterByVehicle(query: $query, request: $request);
    }

    /**
     * @param Builder|HasMany $query
     * @param VehicleExpensesRequest|ExpensesListRequest $request
     * @return Builder|HasMany
     */
    private function filter(Builder|HasMany $query, VehicleExpensesRequest|ExpensesListRequest $request): Builder|HasMany
    {
        $query->select([
            'vehicle_id',
            DB::raw('total as cost'),
            DB::raw('created_at as created_at'),
            DB::raw("'" . ExpensesEnums::Service . "' as type")
        ]);

        if ($request->validated('min_cost')) {
            $query->where('total', '>=', $request->validated('min_cost'));
        }

        if ($request->validated('max_cost')) {
            $query->where('total', '<=', $request->validated('max_cost'));
        }

        if ($request->validated('min_creation_date')) {
            $query->where('created_at', '>=', $request->validated('min_creation_date'));
        }

        if ($request->validated('max_creation_date')) {
            $query->where('created_at', '<=', $request->validated('max_creation_date'));
        }

        if ($request->validated('service_status')) {
            $query->whereIn('status', $request->validated('service_status'));
        }
        return $query;
    }

}
