<?php

namespace Database\Factories;

use Domain\Flights\Models\Flight;
use Domain\Passengers\Models\Passenger;
use Faker\Provider\DateTime;
use Faker\Provider\sv_SE\Person;
use Faker\Provider\sv_SE\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Support\Helper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Passengers\Models\Passenger>
 */
class PassengerFactory extends Factory
{
    protected $model = Passenger::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));

        return [
            'personal_id_number' => Helper::formatSSN($this->faker->unique()->personalIdentityNumber(
                DateTime::dateTimeBetween('-80 years', '-24 years')
            )),
            'name' => $this->faker->name,
            'email' => 'prefix_'.random_int(1, 99999).'_'.$this->faker->unique()->safeEmail, // prefix to avoid duplicate emails
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->countryCode,
            'nationality' => $this->faker->countryCode,
            'passport_number' => $this->faker->numberBetween(100000000, 999999999),
            'passport_expiration_date' => DateTime::dateTimeBetween('now', '+10 years'),
            'passport_issuing_country' => $this->faker->countryCode,
            'passport_issuing_date' => DateTime::dateTimeBetween('-10 years', 'now'),
        ];
    }
}
