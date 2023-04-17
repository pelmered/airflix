<?php

namespace Domain\Flights\Http\Controllers;

use Domain\Flights\DataObjects\FlightData;
use Domain\Flights\Models\Flight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;
use Support\BaseClasses\BaseApiResourceController;

class FlightsApiController extends BaseApiResourceController
{
    /**
     * Display the single booking resource.
     *
     * @param  Flight  $flight
     *
     * @return JsonResponse
     */
    public function show(Flight $flight): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'type' => 'flight',
            'uuid' => $flight->uuid,
            'flight_number' => $flight->flight_number,
            'data' => FlightData::fromModel($flight),
            'links' => [
                'self' => route('api.flights.show', ['flight' => $flight]),
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return PaginatedDataCollection
     */
    public function index(Request $request): PaginatedDataCollection
    {
        return FlightData::collection(Flight::paginate($this->getPerPage()));
    }

    /**
     *
     *
     * @return JsonResponse
     */
    public function availableSeats(Flight $flight): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'type' => 'flight_available_seats',
            'uuid' => $flight->uuid,
            'flight_number' => $flight->flight_number,
            'data' => $flight->getAvailableSeats(),
            'links' => [
                'self' => route('api.flights.show', ['flight' => $flight]),
            ],
        ]);
    }
}
