<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Models\Vehicle;
use App\Services\VehicleExpensesService;
use Illuminate\Http\JsonResponse;

/**
 * @private VehicleExpensesService $vehicleExpensesService
 */
class VehicleExpenseController extends Controller
{
    private VehicleExpensesService $vehicleExpensesService;

    public function __construct(VehicleExpensesService $vehicleExpensesService)
    {
        $this->vehicleExpensesService = $vehicleExpensesService;
    }

    /**
     * @param ExpensesListRequest $request
     * @return JsonResponse
     */
    public function index(ExpensesListRequest $request): JsonResponse
    {
        return response()->json($this->vehicleExpensesService->getExpenses($request));
    }

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return JsonResponse
     */
    public function show(Vehicle $vehicle, VehicleExpensesRequest $request): JsonResponse
    {
        return response()->json($this->vehicleExpensesService->getExpensesByVehicle(vehicle: $vehicle, request: $request));
    }
}
