<?php

namespace Domain\Tickets\Actions;

use Domain\Flights\Exceptions\NoAvailableSeatsException;
use Domain\Flights\Exceptions\SeatInvalidException;
use Domain\Flights\Exceptions\SeatNotAvailableException;
use Domain\Flights\Models\Flight;
use Domain\Tickets\DataObjects\TicketData;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;
use Support\Exceptions\NotPersistedApiException;

class CreateTicketAction
{
    /**
     * @param TicketData $ticketData
     *
     * @return Ticket
     * @throws NotPersistedApiException
     * @throws NoAvailableSeatsException
     * @throws SeatNotAvailableException
     */
    public function execute(TicketData $ticketData): Ticket
    {
        $flight = $ticketData?->flight?->uuid
            ? Flight::find($ticketData->flight->uuid)
            : Flight::whereFlightNumber($ticketData->flight->flight_number)->first();


        if (!$flight?->isSeatValid($ticketData->seat_number)) {
            throw new SeatInvalidException();
        }

        if (!$flight?->isSeatAvailable($ticketData->seat_number)) {
            throw new SeatNotAvailableException();
        }

        $ticket = new Ticket();
        $ticket->flight_number = $ticketData->flight->flight_number;
        $ticket->passenger_uuid = $ticketData->passenger->uuid;
        $ticket->seat_number = $ticketData->seat_number ?: $flight?->getRandomFreeSeat();
        $ticket->price = $ticketData->price ?: $ticketData->flight->price;
        $ticket->status = TicketStatus::CONFIRMED;

        if (!$ticket->save()) {
            throw new NotPersistedApiException();
        }

        return $ticket;
    }
}
