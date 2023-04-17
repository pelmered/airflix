<?php
namespace Domain\Flights\Exceptions;

use Support\Exceptions\APIException;

class SeatNotAvailableException extends APIException
{
    protected int $statusCode = 422;

    protected string $errorCode = 'seat_not_available';

    protected $message = 'Seat not available';
}
