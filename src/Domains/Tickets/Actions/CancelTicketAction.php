<?php

namespace Domain\Tickets\Actions;

use App\Exceptions\Api\ApiErrorException;
use App\Exceptions\Api\NotAllowedToBookApiException;
use App\Exceptions\Api\NotPersistedApiException;
use App\Exceptions\Api\UnauthorizedApiException;
use App\Exceptions\Api\UserNotVerifiedApiException;
use App\Exceptions\Api\VehicleNotAvailableApiException;
use Domain\Bookings\DataTransferObjects\BookingData;
use Domain\Bookings\Events\BookingCreated;
use Domain\Bookings\Models\Booking;
use Domain\Price\PriceCalculator;
use Domain\Tickets\DataObjects\FlightData;
use Domain\Tickets\DataObjects\TicketData;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;

class CancelTicketAction
{
    /**
     * @param  TicketData  $ticketData
     *
     * @return Ticket
     */
    public function execute(Ticket $ticket): Ticket
    {
        $ticket->status = TicketStatus::CANCELLED;
        $ticket->save();

        return $ticket;
    }
}
