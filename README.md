# AirFlix

## Description

AirFlix is a minimal API for an airplane ticket management system for demonstration purposes. 

It is quite a bit over-engineered for the scope of this task, but it is a good example of how to structure a larger Laravel project.

### Features
- Create new tickets
- Edit tickets (change seat and passenger)
- Cancel tickets
- Check available seats
- List available flights
- List and create new passengers
- Automatic seat allocation for new tickets
- Basic rate limiting (30 requests per minute)

### Limitations
- No authentication or authorization
- Limited input validation
- Limited error handling
- No billing
- Not full test coverage
- You should only see passengers attached to your user, but this requires authentication.

## Notes

- Built with Laravel 10.0.0, PHP 8.1 and MySQl 8.0
- Sometimes the tests fail (Duplicate entry or No available seats)

## Setup

Setup like a simple Laravel project. See documentation here: https://laravel.com/docs/10.x/installation#initial-configuration

- Clone the repository
- Run `composer install`
- Copy the `.env.example` file to `.env` and set the database credentials.
- run `php artisan migrate:fresh --seed` to create the database tables and seed some data.
- Set up a webserver to serve the project. I used [Valet](https://laravel.com/docs/10.x/valet) for this project.

## Usage

Load the Postman collection from the `postman` folder and run the requests.

## Tests

To run the test suite, run `php artisan test`
This will test that the API responds correctly to the requests(API test suite) and that seats are allocated correctly (Unit test suite).

