<?php

namespace Tests\API;

use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Models\Ticket;
use Tests\API\ResourceStructures\FlightDataStructure;
use Tests\API\ResourceStructures\PassengerDataStructure;
use Tests\TestCase;

class FlightAPITest extends ApiResourceTester
{
    public string $resourceName = 'flights';

    public string $resourceModel = Flight::class;

    public string $resourceData =  FlightDataStructure::class;

    public static function getResource()
    {
        return Flight::factory()->create();
    }
}
