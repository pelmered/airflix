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

class TicketTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $flight = Flight::factory()->create();
        $ticket = Ticket::factory()->flight($flight)->passenger()->create();
    }

    /**
     * @throws SeatNotAvailableException
     * @throws NoAvailableSeatsException
     * @throws BindingResolutionException
     * @throws NotPersistedApiException
     * @throws SeatInvalidException
     */
    public function test_create_ticket_action()
    {
        $createTicketAction = app()->make(CreateTicketAction::class);

        $flight = Flight::factory()->create();
        $passenger = Passenger::factory()->create();

        $ticketData = new TicketData(
            flight: FlightData::fromModel($flight),
            passenger: PassengerData::fromModel($passenger),

        );

        $ticket = $createTicketAction->execute($ticketData);

        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket->uuid);
        $this->assertEquals($flight->flight_number, $ticket->flight_number);
        $this->assertEquals($passenger->uuid, $ticket->passenger_uuid);

        $this->assertTrue($flight->isSeatValid($ticket->seat_number));
        $this->assertFalse($flight->isSeatAvailable($ticket->seat_number));
    }

    /**
     * @throws BindingResolutionException
     * @throws NoAvailableSeatsException
     */
    public function test_edit_ticket_action()
    {
        $createTicketAction = app()->make(EditTicketAction::class);

        $ticket = Ticket::factory()->passenger()->flight()->create();

        $ticketData = TicketData::fromModel($ticket);

        $originalSeatNumber = $ticket->seat_number;
        $seatNumber = $ticket->flight->getRandomFreeSeat();

        $ticketData->passenger = PassengerData::fromModel($ticket->passenger);
        $ticketData->seat_number = $seatNumber;

        $ticket = $createTicketAction->execute($ticket, $ticketData);

        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket->uuid);

        $this->assertTrue($ticket->flight->isSeatValid($ticket->seat_number));
        $this->assertFalse($ticket->flight->isSeatAvailable($ticket->seat_number));
        $this->assertTrue($ticket->flight->isSeatAvailable($originalSeatNumber));
    }

    /**
     * @throws BindingResolutionException
     * @throws NoAvailableSeatsException
     */
    public function test_cancel_ticket_action()
    {
        $createTicketAction = app()->make(EditTicketAction::class);

        $ticket = Ticket::factory()->passenger()->flight()->create();

        $ticketData = TicketData::fromModel($ticket);

        $originalSeatNumber = $ticket->seat_number;
        $seatNumber = $ticket->flight->getRandomFreeSeat();

        $ticketData->passenger = PassengerData::fromModel($ticket->passenger);
        $ticketData->seat_number = $seatNumber;

        $ticket = $createTicketAction->execute($ticket, $ticketData);

        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket->uuid);

        $this->assertTrue($ticket->flight->isSeatValid($ticket->seat_number));
        $this->assertFalse($ticket->flight->isSeatAvailable($ticket->seat_number));
        $this->assertTrue($ticket->flight->isSeatAvailable($originalSeatNumber));
    }


}
