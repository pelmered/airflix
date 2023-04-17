<?php

namespace Tests\API;

use Domain\Tickets\Models\Ticket;
use Domain\Users\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class ApiResourceTester extends TestCase
{
    //use CreatesApplication;
    //use CreatesApplication, DatabaseMigrations;
    //use DatabaseTransactions;

    public string $resourceName = '';

    public string $resourceModel = '';

    public string $resourceData = '';


    abstract public static function getResource();

    /**
     * Test API index view.
     *
     * @return void
     */
    public function test_single(): void
    {
        $resource = static::getResource();

        $response = $this->get(route('api.'.$this->resourceName.'.show', $resource));

        $response->assertStatus(200);

        $this->assertJsonStructure(
            $response,
            static::getSingleJsonStructure($this->resourceData::getResourceStructure())
        );
    }


    /**
     * Test API index view.
     *
     * @return void
     * @throws Exception
     */
    public function test_index(): void
    {
        // Skip if index route does not exist
        if (! Route::has('api.'.$this->resourceName.'.index')) {
            $this->assertTrue(true);

            return;
        }

        $response = $this->get(route('api.'.$this->resourceName.'.index'));

        $response->assertStatus(200);

        $this->assertJsonStructure(
            $response,
            static::getIndexJsonStructure(
                $this->resourceData::getResourceStructure()
            )
        );
    }

    public static function getIndexJsonStructure($resourceStructure, $relationshipsStructure = null)
    {
        return [
            //'status',
            'data' => [
                '*' => $resourceStructure,
            ],
            'links' => [
                '*' => [
                    'url',
                    'label',
                    'active',
                ],
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ];
    }

    public function assertJsonStructure(TestResponse $response, $jsonStructure): void
    {
        try {
            $response->assertJsonStructure($jsonStructure);
        } catch (Exception $e) {
            dump(
                'Invalid JSON: '.$e->getMessage(),
                \GuzzleHttp\json_decode($response->getContent()),
                static::getSingleJsonStructure(
                    $this->resourceData::getResourceStructure(),
                ),
                'Invalid JSON: '.$e->getMessage()
            );

            throw $e;
        }
    }

    public static function getSingleJsonStructure($resourceStructure, $relationshipsStructure = null)
    {
        return [
                   'status',
                   'type',
                   'uuid',
                   'data' => $resourceStructure,
                   'links' => [
                       'self',
                   ],
               ] + (
                   $relationshipsStructure ?
                   ['relationships' => $relationshipsStructure] :
                   ['relationships']
               );
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}
