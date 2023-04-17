<?php

namespace Domain\Flights\Models;

use Domain\Flights\Exceptions\NoAvailableSeatsException;
use Domain\Tickets\Enums\TicketStatus;
use Domain\Tickets\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Traits\Uuidable;

/**
 * Domain\Flights\Models\Flight
 *
 * @property string $flight_number
 * @property string $departure_airport
 * @property string $destination_airport
 * @property string $departure_time
 * @property string $arrival_time
 * @property int $no_of_seats
 * @property int $price
 * @property string $airline
 * @property string $aircraft
 * @property string $flight_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Database\Factories\FlightFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Flight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flight query()
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereAircraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereAirline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereArrivalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereDepartureAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereDestinationAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereFlightNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereFlightStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereNoOfSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereUpdatedAt($value)
 * @property string $uuid
 * @property-read mixed $id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @method static \Illuminate\Database\Eloquent\Builder|Flight whereUuid($value)
 * @mixin \Eloquent
 */
class Flight extends Model
{
    use HasFactory;
    use Uuidable;

    public $incrementing = false; // or null

    protected $primaryKey = 'uuid';

    public function tickets(): hasMany
    {
        return $this->hasMany(Ticket::class, 'flight_number', 'flight_number');
    }

    /**
     * @throws NoAvailableSeatsException
     */
    public function getRandomFreeSeat(): int
    {
        $freeSeats = $this->getAvailableSeats();

        if(empty($freeSeats)) {
            throw new NoAvailableSeatsException();
        }

        return $freeSeats[array_rand($freeSeats)];
    }

    public function getAvailableSeats(): array
    {
        $allSeats = range(1, $this->no_of_seats);
        $occupiedSeats = $this->tickets()
                              ->where('status', '=', TicketStatus::CONFIRMED)
                              ->get()
                              ->pluck('seat_number')
                              ->toArray();

        return array_values(array_diff($allSeats, $occupiedSeats));
    }

    public function isSeatValid($seatNumber): bool
    {
        return $seatNumber > 0 && $seatNumber <= $this->no_of_seats;
    }

    public function isSeatAvailable($seatNumber): bool
    {
        return in_array($seatNumber, $this->getAvailableSeats(), true);
    }
}
