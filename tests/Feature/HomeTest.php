<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * Open homepage as guest
     *
     * @return void
     */
    public function test_it_user_can_open_homepage()
    {
        $response = $this->get('/');

        $response->assertSeeText('Using export/import places?')->assertStatus(200);
    }

    /**
     * User can see export form for guest are not allowed
     * @return [type] [description]
     */
    public function test_it_user_can_open_homepage_with_as_loggedin()
    {
        $this->loginAsUser();

        $response = $this->get('/');

        $response->assertSeeText('Export data from RapidAPI')->assertStatus(200);
    }
}
