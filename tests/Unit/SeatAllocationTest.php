<?php

namespace Tests\Unit;

use Domain\Flights\Exceptions\NoAvailableSeatsException;
use Domain\Flights\Models\Flight;
use Domain\Tickets\Models\Ticket;
use Tests\TestCase;

class SeatAllocationTest extends TestCase
{
    public function test_new_tickets_are_assigned_a_random_seat_number()
    {
        $flight = Flight::factory()->create();
        $ticket = Ticket::factory()->flight($flight)->passenger()->create();

        $this->assertNotNull($ticket->seat_number);
        $this->assertIsInt($ticket->seat_number);

        $this->assertGreaterThanOrEqual(1, $ticket->seat_number);
        $this->assertLessThanOrEqual($flight->no_of_seats, $ticket->seat_number);
    }

    /*
    public function test_seat_assignments_does_not_clash_until_full()
    {
        $flight = Flight::factory()->create([
            'no_of_seats' => 20,
        ]);

        foreach(range(1, 20) as $i) {
            $ticket = Ticket::factory()->flight($flight)->passenger()->confirmed()->create();
            $this->assertNotNull($ticket->seat_number);
            $this->assertIsInt($ticket->seat_number);
            $this->assertGreaterThanOrEqual(1, $ticket->seat_number);
            $this->assertLessThanOrEqual($flight->no_of_seats, $ticket->seat_number);
        }

        $this->assertEquals(20, $flight->tickets()->count());
        $this->assertEquals([], $flight->getAvailableSeats());

        $this->expectExceptionObject(new NoAvailableSeatsException());

        $flight->getRandomFreeSeat();
        Ticket::factory()->flight($flight)->passenger()->create();
    }
    */
}
