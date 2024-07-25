<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensesListRequest;
use App\Http\Requests\VehicleExpensesRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Vehicle;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;

/**
 * @private ExpenseService $expenseService
 */
class ExpenseController extends Controller
{
    private ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Display a listing expenses.
     *
     * @param ExpensesListRequest $request
     * @return JsonResponse
     */
    public function index(ExpensesListRequest $request): JsonResponse
    {
        return response()->json(new ExpenseResource($this->expenseService->getExpenses($request)));
    }

    /**
     *  Display the specified vehicle expenses.
     *
     * @param Vehicle $vehicle
     * @param VehicleExpensesRequest $request
     * @return JsonResponse
     */
    public function show(Vehicle $vehicle, VehicleExpensesRequest $request): JsonResponse
    {
        return response()->json(new ExpenseResource($this->expenseService->getExpensesByVehicle(vehicle: $vehicle, request: $request)));
    }
}
