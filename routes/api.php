<?php

use App\Http\Controllers\ExpenseAggregatorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\VehicleExpenseController;
use Illuminate\Support\Facades\Route;

// Using table view approach
Route::group(['prefix' => '/vehicles'], function () {
    Route::get('/expenses', [VehicleExpenseController::class, 'index'])->middleware('throttle:5,1');

    // the "vehicle.id" (optional) is a key which used in throttle middleware to allow requests 5 times per minute per vehicle
    Route::get('/{vehicle}/expenses', [VehicleExpenseController::class, 'show'])->middleware('throttle:5,1,vehicle.id');
});


// Using query builder and collection
Route::group(['prefix' => '/expenses/aggregator'], function () {
    Route::get('/', [ExpenseAggregatorController::class, 'index'])->middleware('throttle:5,1,vehicle.id');

    // the "vehicle.id" (optional) is a key which used in throttle middleware to allow requests 5 times per minute per vehicle
    Route::get('vehicles/{vehicle}', [ExpenseAggregatorController::class, 'show'])->middleware('throttle:5,1,vehicle.id');
});


// Using Factory design pattern
Route::group(['prefix' => '/expenses'], function () {
    Route::get('/', [ExpenseController::class, 'index'])->middleware('throttle:5,1,vehicle.id');

    // the "vehicle.id" (optional) is a key which used in throttle middleware to allow requests 5 times per minute per vehicle
    Route::get('vehicles/{vehicle}', [ExpenseController::class, 'show'])->middleware('throttle:5,1,vehicle.id');
});


//type[]:fuel
//type[]:insurance
//type[]:service
//vehicle_id:300
//vehicle_name:ter
//plate_number:90804
//min_cost:9
//max_cost:100
//min_creation_date:2020-01-10
//max_creation_date:2000-01-10
//sort_by:created_at
//sort_direction:asc
//per_page:15
