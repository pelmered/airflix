<?php
namespace Domain\Flights\Exceptions;

use Support\Exceptions\APIException;

class NoAvailableSeatsException extends APIException
{
    protected int $statusCode = 422;

    protected string $errorCode = 'no_available_seats';

    protected $message = 'No available seats';
}
