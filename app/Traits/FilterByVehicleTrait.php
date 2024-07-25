<?php

namespace App\Traits;

use App\Http\Requests\ExpensesListRequest;
use Illuminate\Database\Eloquent\Builder;

trait FilterByVehicleTrait
{
    /**
     * @param Builder $query
     * @param ExpensesListRequest $request
     * @return Builder
     */
    private function filterByVehicle(Builder $query, ExpensesListRequest $request): Builder
    {
        if ($request->validated('vehicle_id')) {
            $query->where('vehicle_id', $request->validated('vehicle_id'));
        }

        if ($request->validated('vehicle_name')) {
            $query->whereHas('vehicle', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->validated('vehicle_name') . '%');
            });
        }

        if ($request->validated('plate_number')) {
            $query->whereHas('vehicle', function ($query) use ($request) {
                $query->where('plate_number', 'like', '%' . $request->validated('plate_number') . '%');
            });
        }
        return $query;
    }
}
