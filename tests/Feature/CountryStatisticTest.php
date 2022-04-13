<?php

namespace Tests\Feature;

use Tests\TestCase;

class CountryStatisticTest extends TestCase
{

    /**
     * @return void
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Tests status code and data structure for get-countries API endpoint.
     */
    public function testSuccessfulResponse()
    {
        $this->authorize();
        $response = $this->get('/api/v1/get-countries', ['accept' => 'application/json']);
        $response->assertStatus(200);
        foreach ($response->json() as $item) {
            self::assertArrayHasKeys($item, [
                'id', 'code', 'name', 'statistics_sum_deaths', 'statistics_sum_confirmed', 'statistics_sum_recovered'
            ]);
            self::assertArrayHasKeys($item['name'], ['en', 'ka']);
        }

    }

    /**
     * Tests if unauthorized response status was returned from a per country
     * statistics fetching endpoint when called without token.
     * @return void
     */
    public function testUnauthorizedResponse()
    {
        $response = $this->get('/api/v1/get-countries', ['accept' => 'application/json']);
        $response->assertStatus(401);
    }

}
