<?php

namespace Domain\Flights\DataObjects;

use App\Api\Requests\Bookings\CreateTicket;
use DateTime;
use Domain\Flights\Models\Flight;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Support\Data\Transformers\BooTolStringTransformer;
use Support\Data\Transformers\MoneyToFloatTransformer;
use Support\Helper;

class FlightData extends Data
{
    public function __construct(
        public ?string $uuid,
        public string $flight_number,
        public ?string $departure_airport,
        public ?string $destination_airport,
        public ?DateTime $departure_time,
        public ?DateTime $arrival_time,
        public ?string $no_of_seats,
        public ?string $price,
        public ?string $airline,
        public ?string $aircraft,
        public ?string $flight_status,
    ) {
    }

    public static function fromModel(Flight $flight): self
    {
        return new self(
            uuid: null,
            flight_number: $flight->flight_number,
            departure_airport: $flight->departure_airport,
            destination_airport: $flight->destination_airport,
            departure_time: $flight->departure_time,
            arrival_time: $flight->arrival_time,
            no_of_seats: $flight->no_of_seats,
            price: $flight->price,
            airline: $flight->airline,
            aircraft: $flight->aircraft,
            flight_status: $flight->flight_status,
        );
    }

    public function toModel(): Flight
    {
        return Flight::whereFlightNumber($this->flight_number)->first();
    }
}
