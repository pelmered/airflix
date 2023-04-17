<?php
namespace Domain\Passengers\Http\Controllers;

use Domain\Flights\DataObjects\FlightData;
use Domain\Flights\Models\Flight;
use Domain\Passengers\DataObjects\PassengerData;
use Domain\Passengers\Models\Passenger;
use Domain\Tickets\Actions\CreateTicketAction;
use Domain\Tickets\Actions\EditTicketAction;
use Domain\Tickets\DataObjects\TicketData;
use Domain\Tickets\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;
use Support\BaseClasses\BaseApiResourceController;

class PassengersApiController extends BaseApiResourceController
{
    /**
     * Display the single booking resource.
     *
     * @param  Passenger  $passenger
     *
     * @return JsonResponse
     */
    public function show(Passenger $passenger): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'type' => 'flight',
            'uuid' => $passenger->uuid,
            'data' => PassengerData::fromModel($passenger),
            'links' => [
                'self' => route('api.flights.show', ['flight' => $passenger]),
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return PaginatedDataCollection
     */
    public function index(Request $request): PaginatedDataCollection
    {
        return PassengerData::collection(Flight::paginate($this->getPerPage()));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param TicketData $ticketData
     * @param CreateTicketAction $createTicketAction
     *
     * @return JsonResponse
     */
    public function store(PassengerData $ticketData, CreateTicketAction $createTicketAction): JsonResponse
    {
        $ticket = $createTicketAction->execute($ticketData);

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'Ticket created',
                'type' => 'ticket',
                'uuid' => $ticket->uuid,
                'data' => PassengerData::fromModel($ticket),
                'links' => [
                    'self' => route('api.tickets.show', ['ticket' => $ticket]),
                ],
            ],
            201
        );
    }

    /**
     * @param  Passenger            $ticket
     * @param  PassengerData        $passengerData
     * @param  EditTicketAction  $editTicketAction
     *
     * @return JsonResponse
     */
    public function update(Passenger $ticket, PassengerData $passengerData, EditTicketAction $editTicketAction): JsonResponse
    {
        $ticket = $editTicketAction->execute($ticket, $passengerData );

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'Ticket updated',
                'uuid' => $ticket->uuid,
                'data' => PassengerData::fromModel($ticket),
                'links' => [
                    'self' => route('api.tickets.show', ['ticket' => $ticket]),
                ],
            ],
            200
        );
    }
}
