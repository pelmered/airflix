<?php

namespace Database\Factories;

use Domain\Bookings\Models\Booking;
use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;
use Domain\Vehicles\Enums\KeyLocation;
use Domain\Vehicles\Enums\VehicleStatus;
use Faker\Provider\DateTime;
use Faker\Provider\sv_SE\Person;
use Faker\Provider\sv_SE\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Support\Helper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Tickets\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /*
        dd($this->getRawAttributes());
        $flight = (Flight::inRandomOrder()->limit(1)->first()) ?? Flight::factory()->create();
        $passenger = (Passenger::inRandomOrder()->limit(1)->first()) ?? Passenger::factory()->create();
        */
        return [
            //'flight_number' => $flight->flight_number,
            //'passenger_uuid' => $passenger->id,
            //'seat_number' => $flight->getRandomFreeSeat(),
            'price' => $this->faker->numberBetween(100, 10000),
            //'status' => $this->faker->randomElement(TicketStatus::all()),
            'status' => TicketStatus::CONFIRMED,
        ];
    }

    public function cancelled(): TicketFactory
    {
        return $this->state([
            'status' => TicketStatus::CANCELLED,
        ]);
    }

    public function flight($flight = null): TicketFactory
    {
        if (!$flight) {
            $flight = (Flight::inRandomOrder()->limit(1)->first()) ?? Flight::factory()->create();
        }

        return $this->state([
            'flight_number' => $flight->flight_number,
            //'passenger_uuid' => $passenger->id,
            'seat_number' => $flight->getRandomFreeSeat(),
        ]);
    }

    public function passenger($passenger = null): TicketFactory
    {
        if (!$passenger) {
            $passenger = (Passenger::inRandomOrder()->limit(1)->first()) ?? Passenger::factory()->create();
        }

        return $this->state([
            'passenger_uuid' => $passenger->id,
        ]);
    }

}
