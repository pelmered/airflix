<?php
namespace Domain\Flights\Exceptions;

use Support\Exceptions\APIException;

class SeatInvalidException extends APIException
{
    protected int $statusCode = 422;

    protected string $errorCode = 'seat_invalid';

    protected $message = 'Seat number is invalid';
}
