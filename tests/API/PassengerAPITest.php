<?php

namespace Tests\API;

use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Models\Ticket;
use Tests\API\ResourceStructures\PassengerDataStructure;
use Tests\API\ResourceStructures\TicketDataStructure;
use Tests\TestCase;

class PassengerAPITest extends ApiResourceTester
{
    public string $resourceName = 'passengers';

    public string $resourceModel = Passenger::class;

    public string $resourceData =  PassengerDataStructure::class;

    public static function getResource()
    {
        return Passenger::factory()->create();
    }
}
