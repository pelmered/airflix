<?php

use Domain\Flights\Http\Controllers\FlightsApiController;
use Domain\Passengers\Http\Controllers\PassengersApiController;
use Domain\Tickets\Http\Controllers\TicketsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$api_version = 'v1';

Route::middleware(['api', 'throttle:30,1'])
     ->prefix($api_version.'/')
     ->group(function (): void {
         Route::apiResource('tickets', TicketsApiController::class)
              ->except(['destroy']);


         Route::patch('tickets/{ticket}/cancel', [TicketsApiController::class, 'cancel'])
              ->name('tickets.cancel');


         Route::apiResource('flights', FlightsApiController::class)
              ->only(['show', 'index']);

         Route::get('flights/{flight}/available-seats', [FlightsApiController::class, 'availableSeats'])
              ->name('flights.available-seats');

         Route::apiResource('passengers', PassengersApiController::class)
              ->except(['destroy']);
     });

