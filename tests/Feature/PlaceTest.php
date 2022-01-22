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

    protected function loginAsUser() 
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user);
        return $response;
    }

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
     * A find page create places.
     *
     * @return void
     */
    public function test_places_create_page()
    {
        $this->loginAsUser();

        $response = $this->get('/places/create');

        $response->assertStatus(200);
    }

    public function test_it_store_new_palce()
    {
        $response = $this->loginAsUser();

        $response = $response->post('/places/create/store', [
            'city' => 'Sarajevo',
            'country' => 'Bosnia and Herzegovina',
            'date' => '2011-12-03',
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect('/places');
    } 

    public function test_it_store_new_place_with_error_required_fields()
    {
        $response = $this->loginAsUser();

        $response = $response->post('/places/create/store', [
            'city' => '',
            'country' => '',
            'date' => '',
        ]);

        $response->assertSessionHasErrors(['city', 'country', 'date']);
    } 

    public function test_it_user_can_see_export_form()
    {
        $response = $this->loginAsUser();

        $response->get('/')->assertSee('Select country and city to find suggested three hotels or check daily weather forecast')->assertSee('Choose country...');

    }

    public function test_it_user_can_submit_export_form_with_error_required_fields()
    {
        $response = $this->loginAsUser();

        $response = $response->post('/places/search', [
            'country' => '',
            'city' => '',
            'date' => ''
        ]);

        $response->assertSessionHasErrors([
            'city' => 'The city field is required when country is not present.',
            'country' => 'The country field is required when city is not present.'
        ]);
    }

    public function test_it_user_can_submit_export_form()
    {
        $response = $this->loginAsUser();

        $place = Place::factory()->create();

        $response = $response->post('/places/search', [
            'country' => $place->country,
            'city' => $place->city,
            'date' => $place->date
        ])->assertViewIs('place.search')->assertSeeText('Export near hotels');
    }

    public function test_it_user_can_see_page_export_hotels() 
    {
        $response = $this->loginAsUser();

        $place = Place::factory()->create([
            'city' => 'Sarajevo',
            'country' => 'Bosnia and Herzegovina',
            'date' => '2020-02-19'
        ]);

        $response = $response->get('/places/'.$place->id.'/export/hotels')->assertViewHasAll(['place', 'clientHotels', 'clientWeather'])->assertSeeText($place->city.', '.$place->country);

        $response->assertStatus(200);
    }

    public function test_it_user_can_submit_place_export_hotels() 
    {
        $response = $this->loginAsUser();

        $place = Place::factory()->create([
            'city' => 'Sarajevo',
            'country' => 'Bosnia and Herzegovina',
            'date' => '2020-02-19'
        ]);

        // Open clients
        $clientHotels = HotelClient::searchByGroup($place->city, 'HOTEL_GROUP', $limit = 3);
        $clientWeather = WeatherClient::currentWeather($place->city);

        $response = $response->post('/places/'.$place->id.'/export/hotels/store', [
            'client_hotels' => [],
            'client_weather' => json_encode($clientWeather),
            'export_weather_check' => 1
        ])->assertRedirect('/places/'.$place->id.'/view');

        dd($response);

    }
}
