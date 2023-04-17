<?php

namespace Tests\Unit;

use Domain\Flights\DataObjects\FlightData;
use Domain\Flights\Exceptions\NoAvailableSeatsException;
use Domain\Flights\Exceptions\SeatInvalidException;
use Domain\Flights\Exceptions\SeatNotAvailableException;
use Domain\Flights\Models\Flight;
use Domain\Passengers\DataObjects\PassengerData;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Actions\CreateTicketAction;
use Domain\Tickets\Actions\EditTicketAction;
use Domain\Tickets\DataObjects\TicketData;
use Domain\Tickets\Models\Ticket;
use Illuminate\Contracts\Container\BindingResolutionException;
use Support\Exceptions\NotPersistedApiException;
use Tests\TestCase;

class PassengerTest extends TestCase
{
    public function test_example()
    {
        $this->assertTrue(true);
    }
}
