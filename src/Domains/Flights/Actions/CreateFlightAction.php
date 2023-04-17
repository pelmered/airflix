<?php

namespace Domain\Flights\Actions;

use App\Exceptions\Api\ApiErrorException;
use App\Exceptions\Api\NotPersistedApiException;
use App\Exceptions\Api\UnauthorizedApiException;
use Domain\Tickets\DataObjects\FlightData;

class CreateFlightAction
{
    /**
     * @param  FlightData  $flightData
     *
     * @return Flight
     *
     * @throws ApiErrorException
     * @throws NotPersistedApiException
     * @throws UnauthorizedApiException
     */
    public function execute(FlightData $flightData)
    {
    }
}
