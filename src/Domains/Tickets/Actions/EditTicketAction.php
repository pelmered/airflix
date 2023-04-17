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
use Domain\Tickets\Models\Ticket;
use Domain\Vehicles\Enums\TicketStatus;
use InvalidArgumentException;
use Support\Helper;

class EditTicketAction
{
    /**
     * @param  TicketData  $ticketData
     *
     * @return Ticket
     */
    public function execute(Ticket $ticket, TicketData $ticketData): Ticket
    {
        $allowedFields = [
            'passenger_uuid',
            'seat_number',
        ];

        foreach ($ticketData->all() as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $ticket->{$key} = $value;
            }
        }

        $ticket->update($ticketData->all());

        return $ticket;
    }
}
