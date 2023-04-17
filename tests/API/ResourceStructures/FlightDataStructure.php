<?php
namespace Tests\API\ResourceStructures;

class FlightDataStructure implements DataStructureInterface
{
    public static function getResourceStructure(): array
    {
        return [
            'flight_number',
            'departure_airport',
            'destination_airport',
            'departure_time',
            'arrival_time',
            'no_of_seats',
            'price',
            'airline',
            'aircraft',
            'flight_status',
        ];
    }
}
