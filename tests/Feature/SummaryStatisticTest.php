<?php

namespace Tests\Feature;

use Tests\TestCase;

class SummaryStatisticTest extends TestCase
{
    /**
     * Tests status code and data structure for get-summary API endpoint.
     * @return void
     */
    public function testSuccessfulResponse()
    {
        $this->authorize();
        $response = $this->get('/api/v1/get-summary', ['accept' => 'application/json']);
        $response->assertStatus(200)->assertJsonStructure(['total_confirmed', 'total_deaths', 'total_recovered']);
    }

    /**
     * Tests if unauthorized response status was returned from a per country
     * statistics fetching endpoint when called without token.
     * @return void
     */
    public function testUnauthorizedResponse()
    {
        $response = $this->get('/api/v1/get-summary', ['accept' => 'application/json']);
        $response->assertStatus(401);
    }

}
