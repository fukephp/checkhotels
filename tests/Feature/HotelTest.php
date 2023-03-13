<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HotelTest extends TestCase
{
    /**
     * Test check if user can see hotels page
     */
    public function test_it_user_can_see_hotels_page()
    {
        $response = $this->loginAsUser();

        $response = $this->get('/hotels');

        $response->assertStatus(200);
    }

    /**
     * Test filter form validation
     * @return [type] [description]
     */
    public function test_it_user_can_user_filter_form_without_fill_input()
    {
        $response = $this->loginAsUser();

        $response = $this->from('/hotels')->post('/hotels', 
            [
                'country' => '',
                'city' => ''
            ]
        );

        $response->assertSessionHasErrors(['country', 'city'])->assertRedirect('/hotels');
    } 

    /**
     * Test filter form with filled inputs
     * @return [type] [description]
     */
    public function test_it_user_can_user_filter_form_with_input()
    {
        $response = $this->loginAsUser();

        $response = $this->from('/hotels')->post('/hotels', 
            [
                'country' => 'Spain',
                'city' => 'Madrid'
            ]
        );
        
        $response->assertViewHasAll(['hotels'])->assertStatus(200);
    }
}
