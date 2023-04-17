<?php

namespace Domain\Passengers\Models;

use Domain\Flights\Models\Flight;
use Domain\Tickets\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Traits\Uuidable;

/**
 * Domain\Passengers\Models\Passenger
 *
 * @property int $id
 * @property string $name
 * @property string $personal_id_number
 * @property string $email
 * @property string $phone_number
 * @property string $address
 * @property string $city
 * @property string $country
 * @property string $nationality
 * @property string $passport_number
 * @property string $passport_expiration_date
 * @property string $passport_issuing_country
 * @property string $passport_issuing_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Database\Factories\PassengerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger query()
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger wherePassportExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger wherePassportIssuingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger wherePassportIssuingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger wherePassportNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger wherePersonalIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereUpdatedAt($value)
 * @property string $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @method static \Illuminate\Database\Eloquent\Builder|Passenger whereUuid($value)
 * @mixin \Eloquent
 */
class Passenger extends Model
{
    use HasFactory;
    use Uuidable;

    public $incrementing = false; // or null

    protected $primaryKey = 'uuid';

}
