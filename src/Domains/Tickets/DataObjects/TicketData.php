<?php
namespace Domain\Tickets\DataObjects;

use Domain\Flights\DataObjects\FlightData;
use Domain\Flights\Models\Flight;
use Domain\Passengers\DataObjects\PassengerData;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;
use Illuminate\Http\Client\Request;
use Spatie\LaravelData\Data;
use Support\Exceptions\NotFoundApiException;

class TicketData extends Data
{
    public function __construct(
        public ?FlightData $flight,
        public ?PassengerData $passenger,
        public ?int $seat_number,
        public ?int $price,
        public ?string $uuid = null,
        public ?string $status = TicketStatus::CONFIRMED,
        public ?string $updated_at = null,
        public ?string $created_at = null,
    ) {
    }

    public static function rules()
    {
        return [
            'flight_number' => ['string'],
            'passenger_uuid' => ['string'],
            'seat_number' => ['nullable', 'integer'],
            'price' => ['nullable', 'integer'],
        ];
    }

    public static function fromModel(Ticket $ticket): self
    {
        return new self(
            flight:      FlightData::fromModel($ticket->flight),
            passenger:   PassengerData::fromModel($ticket->passenger),
            seat_number: $ticket->seat_number,
            price:       $ticket->price,
            uuid:        $ticket->uuid,
            status:      $ticket->status,
            updated_at:  $ticket->updated_at,
            created_at:  $ticket->created_at,
        );
    }


    public static function fromRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $flight = Flight::where('flight_number', $request->flight_number)->firstOr(['*'], function () {
                throw (new NotFoundApiException('Flight not found'))
                    ->setErrors(['flight_number' => 'The provided flight number is invalid.'])
                    ->setStatusCode(422);
            });
            $passenger = Passenger::where('uuid', $request->passenger_uuid)->firstOr(['*'], function () {
                throw (new NotFoundApiException('Passenger not found'))
                    ->setErrors(['passenger_uuid' => 'The provided passenger UUID is invalid.'])
                    ->setStatusCode(422);
            });
        }

        if ($request->getMethod() === 'PATCH') {
            $flight = $request?->ticket?->flight;
            $passenger = $request?->ticket?->passenger;
        }

        return new self(
            flight:      FlightData::fromModel($flight),
            passenger:   PassengerData::fromModel($passenger),
            seat_number: $request->seat_number ?? $flight->getRandomFreeSeat(),
            price:       $request->price ?? $flight->price,
        );
    }

    public function toInsert(): array
    {
        return [
            'flight_number' => $this->flight->flight_number,
            'passenger_uuid' => $this->passenger->uuid,
            'seat_number' => $this->seat_number,
            //'price' => $this->price,
        ];
    }

}
