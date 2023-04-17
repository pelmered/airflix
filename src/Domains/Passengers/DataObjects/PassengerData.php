<?php

namespace Domain\Passengers\DataObjects;

use App\Api\Requests\Bookings\CreateTicket;
use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Spatie\LaravelData\Data;

class PassengerData extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $personal_id_number,
        public ?string $email,
        public ?string $phone_number,
        public ?string $address,
        public ?string $city,
        public ?string $country,
        public ?string $nationality,
        public ?string $passport_number,
        public ?string $passport_expiration_date,
        public ?string $passport_issuing_country,
        public ?string $passport_issuing_date,
        public ?string $uuid = null,
        public ?string $updated_at = null,
        public ?string $created_at = null,
    ) {
    }


    public static function fromModel(Passenger $passenger): self
    {
        return new self(
            name:                     $passenger->name,
            personal_id_number:       $passenger->personal_id_number,
            email:                    $passenger->email,
            phone_number:             $passenger->phone_number,
            address:                  $passenger->address,
            city:                     $passenger->city,
            country:                  $passenger->country,
            nationality:              $passenger->nationality,
            passport_number:          $passenger->passport_number,
            passport_expiration_date: $passenger->passport_expiration_date,
            passport_issuing_country: $passenger->passport_issuing_country,
            passport_issuing_date:    $passenger->passport_issuing_date,
            uuid:                     $passenger->uuid,
            updated_at:               $passenger->updated_at,
            created_at:               $passenger->created_at,
        );
    }

    public static function fromStoreRequest(CreateTicket $request)
    {
        return new self(
            email:              $user->email,
            firstname:          $user->first_name,
            lastname:           $user->last_name,
            phone_number:       $user->phone_number,
            personal_id_number: $user->personal_id_number,
            birth_date:         $user->getDateOfBirth()->isoFormat('YYYY-MM-DD'),
            preferred_locale:   $user->preferred_locale ?? null,
            admin_comments:     $user->admin_comment,
            full_address:       $user->homeAddress()->first()?->toString("\n"),
            postal_code:        $user->postal_code,
            city:               $user->city,
            country:            $user->country,
            updated_at:         $user->updated_at?->toISOString(),
            created_at:         $user->created_at?->toISOString(),
        );
    }
}
