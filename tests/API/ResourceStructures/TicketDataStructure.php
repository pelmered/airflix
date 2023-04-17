<?php
namespace Tests\API\ResourceStructures;

class TicketDataStructure implements DataStructureInterface
{
    public static function getResourceStructure(): array
    {
        return [
            'uuid',
            'status',
            'seat_number',
            'updated_at',
            'created_at',
            'flight' => FlightDataStructure::getResourceStructure(),
            'passenger' => PassengerDataStructure::getResourceStructure(),
        ];
    }
}
