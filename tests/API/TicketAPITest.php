<?php

namespace Tests\API;

use Domain\Bookings\Models\Booking;
use Domain\Flights\Exceptions\SeatInvalidException;
use Domain\Flights\Exceptions\SeatNotAvailableException;
use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;
use Domain\Users\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Tests\API\ResourceStructures\TicketDataStructure;

class TicketAPITest extends ApiResourceTester
{
    public string $resourceName = 'tickets';

    public string $resourceModel = Ticket::class;

    public string $resourceData =  TicketDataStructure::class;
    public static function getResource()
    {
        return Ticket::factory()->flight()->passenger()->create();
    }


    public function test_create_ticket(): void
    {
        $flight = Flight::factory()->create();
        $passenger = Passenger::factory()->create();

        $response = $this->post(route('api.tickets.store'), [
            'flight_number' => $flight->flight_number,
            'passenger_uuid' => $passenger->uuid,
            'seat_number' => 1,
            'price' => 100,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'status'  => 'ok',
            'message' => 'Ticket created',
            'data'    => [
                'flight' => [
                    'flight_number' => $flight->flight_number,
                ],
                'passenger' => [
                    'uuid' => $passenger->uuid,
                ],
                'seat_number' => 1,
                'price' => 100,
            ],
        ]);

        $this->assertJsonStructure($response, static::getSingleJsonStructure($this->resourceData::getResourceStructure()));

        $this->assertDatabaseHas('tickets', [
            'flight_number' => $flight->flight_number,
            'passenger_uuid' => $passenger->uuid,
            'seat_number' => 1,
            'price' => 100,
        ]);
    }

    public function test_edit_ticket()
    {
        $ticket = Ticket::factory()->flight()->passenger()->create();

        $freeSeats = $ticket->flight->getAvailableSeats();

        $seat_number = $freeSeats[array_rand($freeSeats)];

        $response = $this->patch(route('api.tickets.update', $ticket), [
            'seat_number' => $seat_number,
        ]);

        $response->assertStatus(200);

        $ticket->refresh();
        $this->assertEquals($seat_number, $ticket->seat_number);

        $response->assertJson([
            'status'  => 'ok',
            'message' => 'Ticket updated',
            'uuid'    => $ticket->uuid,
            'data'    => [
                'uuid' => $ticket->uuid,
                'status' => TicketStatus::CONFIRMED,
                'seat_number' => $seat_number,
            ],
        ]);
    }

    public function test_cancel_ticket()
    {
        $ticket = Ticket::factory()->flight()->passenger()->create();

        $response = $this->patch(route('api.tickets.cancel', $ticket));

        $response->assertStatus(200);

        $ticket->refresh();

        $this->assertEquals(TicketStatus::CANCELLED, $ticket->status);

        $response->assertJson([
            'status'  => 'ok',
            'message' => 'Ticket cancelled',
            'uuid'    => $ticket->uuid,
            'data'    => [
                'uuid' => $ticket->uuid,
                'status' => TicketStatus::CANCELLED,
            ],
        ]);

    }

    public function test_create_ticket_with_invalid_flight_number()
    {
        $flight = Flight::factory()->create();
        $passenger = Passenger::factory()->create();

        $response = $this->post(route('api.tickets.store'), [
            'flight_number' => 'invalid-flight-number',
            'passenger_uuid' => $passenger->uuid,
            'seat_number' => 1,
            'price' => 100,
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'status'  => 'error',
            'error_code' => 'not_found',
            'message' => 'Flight not found',
            'errors'  => [
                'flight_number' => 'The provided flight number is invalid.',
            ],
        ]);
    }

    public function test_create_ticket_with_invalid_passenger_uuid(): void
    {
        $flight = Flight::factory()->create();
        $passenger = Passenger::factory()->create();

        $response = $this->post(route('api.tickets.store'), [
            'flight_number' => $flight->flight_number,
            'passenger_uuid' => 'invalid-passenger-uuid',
            'seat_number' => 1,
            'price' => 100,
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'status'  => 'error',
            'error_code' => 'not_found',
            'message' => 'Passenger not found',
            'errors'  => [
                'passenger_uuid' => 'The provided passenger UUID is invalid.',
            ],
        ]);
    }


    public function test_create_ticket_with_invalid_seat_number(): void
    {
        $flight = Flight::factory()->create();
        $passenger = Passenger::factory()->create();

        $response = $this->post(route('api.tickets.store'), [
            'flight_number' => $flight->flight_number,
            'passenger_uuid' => $passenger->uuid,
            'seat_number' => 100,
            'price' => 100,
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'status'  => 'error',
            'error_code' => 'seat_invalid',
            'message' => 'Seat number is invalid',
        ]);
    }

    public function test_create_ticket_with_occupied_seat_number(): void
    {
        $flight = Flight::factory()->create();
        $passenger = Passenger::factory()->create();

        Ticket::factory()->passenger()->create([
            'flight_number' => $flight->flight_number,
            'seat_number' => 2,
        ]);

        $response = $this->post(route('api.tickets.store'), [
            'flight_number' => $flight->flight_number,
            'passenger_uuid' => $passenger->uuid,
            'seat_number' => 2,
            'price' => 100,
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'status'  => 'error',
            'error_code' => 'seat_not_available',
            'message' => 'Seat not available',
        ]);
    }
}
