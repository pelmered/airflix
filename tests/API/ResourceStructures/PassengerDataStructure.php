<?php
namespace Tests\API\ResourceStructures;

class PassengerDataStructure implements DataStructureInterface
{
    public static function getResourceStructure(): array
    {
        return [
            'name',
            'personal_id_number',
            'email',
            'phone_number',
            'address',
            'city',
            'country',
            'nationality',
            'passport_number',
            'passport_expiration_date',
            'passport_issuing_country',
            'passport_issuing_date',
        ];
    }
}
