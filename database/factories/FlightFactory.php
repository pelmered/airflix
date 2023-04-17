<?php

namespace Database\Factories;

use Domain\Flights\Models\Flight;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Flight>
 */
class FlightFactory extends Factory
{
    protected $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        $departureTime = $this->faker->dateTimeBetween('now', '+1 year');

        return [
            'flight_number' => strtoupper(Str::random(2)).$this->faker->unique()->numberBetween(100, 999),
            'departure_airport' => $this->faker->city,
            'destination_airport' => $this->faker->city,
            'departure_time' => $this->faker->dateTimeBetween('now', '+1 year'),
            'arrival_time' => $departureTime->modify('+'.random_int(30, 18*60).' minutes'),
            'no_of_seats' => $this->faker->numberBetween(5, 10),
            'price' => $this->faker->numberBetween(100, 10000),
            'airline' => $this->faker->company,
            'aircraft' => '',
            'flight_status' => 'scheduled',
        ];
    }
}
