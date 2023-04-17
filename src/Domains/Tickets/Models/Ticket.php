<?php

namespace Domain\Tickets\Models;

use Domain\Bookings\Models\Booking;
use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Domain\Vehicles\Models\Vehicle;
use Domain\Vehicles\Scopes\NotAvailableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\Traits\Uuidable;

/**
 * Domain\Tickets\Models\Ticket
 *
 * @property int $id
 * @property string $flight_number
 * @property int $passenger_uuid
 * @property int $seat_number
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Flight|null $flights
 * @property-read Passenger $passenger
 * @method static \Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereFlightNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket wherePassengerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereSeatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUpdatedAt($value)
 * @property string $uuid
 * @property-read Flight $flight
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket wherePassengerUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUuid($value)
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatus($value)
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    use HasFactory;
    use Uuidable;

    public $incrementing = false; // or null

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'seat_number',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_number', 'flight_number');
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }
}
