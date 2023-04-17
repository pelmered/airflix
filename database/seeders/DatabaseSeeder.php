<?php
namespace Database\Seeders;

use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws \Exception
     */
    public function run(): void
    {
        Flight::factory(1000)->create()->each(function (Flight $flight) {

            $ticketCount = random_int(0, $flight->no_of_seats);

            for($i = 0; $i < $ticketCount; $i++) {

                //dump($flight->toArray());
                //dump('$ticketCount: '.$ticketCount);

                Ticket::factory()->create([
                    'flight_number' => $flight->flight_number,
                    'passenger_uuid' => Passenger::factory()->create()->uuid,
                    'seat_number' => $flight->getRandomFreeSeat(),
                ]);

                /*
                DB::enableQueryLog();
                ($flight->tickets()->get());
                dd(DB::getQueryLog());
                */


            }

            /*
            dump($flight->toArray());
            Ticket::factory()
                  ->count(random_int(0, $flight->no_of_seats))
                  ->state(function (array $attributes) use($flight): array {
                      return [
                          'flight_number' => $flight->flight_number,
                          'passenger_uuid' => Passenger::factory()->create()->id,
                      ];
                  })->create();
            */
        });
    }
}
