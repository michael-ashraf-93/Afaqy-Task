<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\ExpenseAggregatorService;
use Illuminate\Http\JsonResponse;

class ExpenseAggregatorController extends Controller
{
    /**
     * @var ExpenseAggregatorService
     */
    private ExpenseAggregatorService $expenseAggregatorService;

    /**
     * @param ExpenseAggregatorService $expenseAggregatorService
     */
    public function __construct(ExpenseAggregatorService $expenseAggregatorService)
    {
        $this->expenseAggregatorService = $expenseAggregatorService;
    }

    /**
     * @param ExpensesListRequest $request
     * @return JsonResponse
     */
    public function index(ExpensesListRequest $request): JsonResponse
    {
        return response()->json($this->expenseAggregatorService->getExpenses($request));
    }

    /**
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return JsonResponse
     */
    public function show(Vehicle $vehicle, VehicleExpensesRequest $request): JsonResponse
    {
        $expenses = $this->expenseAggregatorService->getExpensesByVehicle(vehicle: $vehicle, request: $request);
        return response()->json((new VehicleResource($vehicle))->additional(['expenses' => $expenses]));
    }
}
