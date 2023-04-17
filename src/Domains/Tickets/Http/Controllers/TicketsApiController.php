<?php
namespace Domain\Tickets\Http\Controllers;

use Domain\Tickets\Actions\CancelTicketAction;
use Domain\Tickets\Actions\CreateTicketAction;
use Domain\Tickets\Actions\EditTicketAction;
use Domain\Tickets\DataObjects\TicketData;
use Domain\Tickets\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;
use Support\BaseClasses\BaseApiResourceController;

class TicketsApiController extends BaseApiResourceController
{
    /**
     * Display the single booking resource.
     *
     * @param  Ticket  $ticket
     *
     * @return JsonResponse
     */
    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'type' => 'ticket',
            'uuid' => $ticket->uuid,
            'data' => TicketData::fromModel($ticket),
            'links' => [
                'self' => route('api.tickets.show', ['ticket' => $ticket]),
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
        return TicketData::collection(Ticket::paginate($this->getPerPage()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TicketData $ticketData
     * @param CreateTicketAction $createTicketAction
     *
     * @return JsonResponse
     */
    public function store(TicketData $ticketData, CreateTicketAction $createTicketAction): JsonResponse
    {
        $ticket = $createTicketAction->execute($ticketData);

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'Ticket created',
                'type' => 'ticket',
                'uuid' => $ticket->uuid,
                'data' => TicketData::fromModel($ticket),
                'links' => [
                    'self' => route('api.tickets.show', ['ticket' => $ticket]),
                ],
            ],
            201
        );
    }

    /**
     * @param  Ticket            $ticket
     * @param  TicketData        $ticketData
     * @param  EditTicketAction  $editTicketAction
     *
     * @return JsonResponse
     */
    public function update(Ticket $ticket, TicketData $ticketData, EditTicketAction $editTicketAction): JsonResponse
    {
        $ticket = $editTicketAction->execute($ticket, $ticketData );

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'Ticket updated',
                'uuid' => $ticket->uuid,
                'data' => TicketData::fromModel($ticket),
                'links' => [
                    'self' => route('api.tickets.show', ['ticket' => $ticket]),
                ],
            ],
            200
        );
    }

    /**
     * @param  UpdateTicket        $request
     * @param  Booking              $booking
     * @param  CancelBookingAction  $cancelBookingCheckAction
     *
     * @return JsonResponse
     *
     * @throws ApiErrorException
     */
    public function cancel(Ticket $ticket, CancelTicketAction $cancelTicketCheckAction)
    {
        $ticket = $cancelTicketCheckAction->execute($ticket);

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'Ticket cancelled',
                'uuid' => $ticket->uuid,
                'data' => TicketData::fromModel($ticket),
            ],
            200
        );
    }
}
