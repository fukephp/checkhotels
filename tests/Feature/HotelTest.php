<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HotelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_user_can_see_hotels_page()
    {
        $response = $this->loginAsUser();

        $response = $this->get('/hotels');

        $response->assertStatus(200);
    }
}
