<?php

namespace Tests\Feature;

use App\Custom\HotelClient;
use App\Custom\WeatherClient;
use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlaceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A find page places.
     *
     * @return void
     */
    public function test_places_page()
    {
        $this->loginAsUser();

        $response = $this->get('/places');

        $response->assertStatus(200);
    } 

    /**
     * Test view is there is export form
     * @return [type] [description]
     */
    public function test_it_user_can_see_export_form()
    {
        $response = $this->loginAsUser();

        $response->get('/')->assertSee('Select country and city to create a new place and find suggested three hotels or check daily weather forecast.')->assertSee('Choose country...');

    }

    /**
     * Test validation for expot form
     * @return [type] [description]
     */
    public function test_it_user_can_submit_export_form_with_error_required_fields()
    {
        $response = $this->loginAsUser();

        $response = $response->post('/places/export', [
            'country' => '',
            'city' => '',
            'date' => ''
        ]);

        $response->assertSessionHasErrors([
            'city' => 'The city field is required.',
            'country' => 'The country field is required.',
            'date' => 'The date field is required.'
        ]);
    }

    /**
     * Test in export form store request with correct redirect page
     * @return [type] [description]
     */
    public function test_it_user_can_submit_export_form()
    {
        $response = $this->loginAsUser();

        $place = Place::factory()->create([
            'city' => 'Sarajevo',
            'country' => 'Bosnia and Herzegovina',
            'date' => '2020-02-19'
        ]);

        $response = $response->post('/places/export', [
            'country' => $place->country,
            'city' => $place->city,
            'date' => $place->date->format('Y-m-d')
        ])->assertRedirect('/places/'.$place->id.'/export/hotels');
    }

    /**
     * After adding in export form it will create a new place and redirect you to /places/:id/export/hotels
     * Test view page with correct data
     * @return [type] [description]
     */
    public function test_it_user_can_see_page_export_hotels() 
    {
        $response = $this->loginAsUser();

        $place = Place::factory()->create([
            'city' => 'Sarajevo',
            'country' => 'Bosnia and Herzegovina',
            'date' => '2020-02-19'
        ]);

        $response = $response->get('/places/'.$place->id.'/export/hotels')->assertViewHasAll(['place', 'clientHotels', 'clientWeather'])->assertSeeText($place->city.', '.$place->country);
    }

    /**
     * When click submit button in page /places/:id/export/hotels
     * Test store request
     * @return [type] [description]
     */
    public function test_it_user_can_submit_place_export_hotels() 
    {
        $response = $this->loginAsUser();

        $place = Place::factory()->create([
            'city' => 'Sarajevo',
            'country' => 'Bosnia & Herzegovina',
            'date' => '2020-02-19',
        ]);

        // Open clients
        $clientHotels = HotelClient::searchByGroup($place->api_destination_id, $place->date->format('Y-m-d'), 'HOTEL_GROUP', $limit = 3);
        $clientWeather = WeatherClient::currentWeather($place->full_name);

        $response = $response->post('/places/'.$place->id.'/export/hotels/store', [
            'client_hotels' => [],
            'client_weather' => json_encode($clientWeather),
            'export_weather_check' => 1
        ])->assertRedirect('/places/'.$place->id.'/view');
    }

    /**
     * In places list there is option to delete single place
     * Reminder: if palace have hotels it will also be deleted
     * @return [type] [description]
     */
    public function test_it_user_can_delete_single_place()
    {
        $response = $this->loginAsUser();

        $response = $this->get('/places')->assertStatus(200);

        $place = Place::factory()->create([
            'city' => 'Sarajevo',
            'country' => 'Bosnia & Herzegovina',
            'date' => '2020-02-19',
        ]);

        $response = $this->get('/places/'.$place->id.'/delete')->assertSessionHas('success', 'Place deleted!');
    } 
}
