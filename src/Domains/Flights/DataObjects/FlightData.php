<?php

namespace Domain\Flights\DataObjects;

use App\Api\Requests\Bookings\CreateTicket;
use Domain\Flights\Models\Flight;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Support\Data\Transformers\BooTolStringTransformer;
use Support\Data\Transformers\MoneyToFloatTransformer;
use Support\Helper;

class FlightData extends Data
{
    /*
     *
            $table->string('flight_number')->primary();
            $table->string('departure_airport');
            $table->string('destination_airport');
            $table->string('departure_time');
            $table->string('arrival_time');
            $table->integer('no_of_seats')->default(32);
            $table->integer('price');
            $table->string('airline');
            $table->string('aircraft');
            $table->string('flight_status');
     */
    public function __construct(
        public ?string $uuid,
        public string $flight_number,
        public ?string $departure_airport,
        public ?string $destination_airport,
        public ?string $departure_time,
        public ?string $arrival_time,
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

    /*
    public static function fromStoreRequest(CreateTicket $request)
    {
        return new self(
        );
    }
    */
}
