<?php
namespace Domain\Passengers\Actions;

use Domain\Flights\Exceptions\NoAvailableSeatsException;
use Domain\Flights\Exceptions\SeatInvalidException;
use Domain\Flights\Exceptions\SeatNotAvailableException;
use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\DataObjects\TicketData;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;
use Support\Exceptions\NotPersistedApiException;

class CreatePassengerAction
{
    /**
     * @param TicketData $ticketData
     *
     * @return Passenger
     * @throws NotPersistedApiException
     * @throws NoAvailableSeatsException
     * @throws SeatNotAvailableException
     */
    public function execute(TicketData $ticketData): Passenger
    {
        $passenger = new Passenger();
        $passenger->flight_number = $ticketData->flight->flight_number;
        $passenger->passenger_uuid = $ticketData->passenger->uuid;
        $passenger->seat_number = $ticketData->seat_number ?: $flight?->getRandomFreeSeat();
        $passenger->price = $ticketData->price ?: $ticketData->flight->price;
        $passenger->status = TicketStatus::CONFIRMED;

        if (!$passenger->save()) {
            throw new NotPersistedApiException();
        }

        return $passenger;
    }
}
