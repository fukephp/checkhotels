<?php

namespace App\Http\Controllers;

use App\Custom\HotelClient;
use App\Custom\WeatherClient;
use App\Http\Requests\ExportPlaceRequest;
use App\Http\Requests\ImportCsvRequest;
use App\Http\Requests\SearchPlaceRequest;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Models\Hotel;
use App\Models\Place;
use App\Models\Weather;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    /**
     * Table list of places
     * @test test_places_page
     */
    public function index()
    {
        $places = Place::all();
        return view('place.index', compact('places'));
    }

    /**
     * View place with hotels list
     */
    public function view($id)
    {
        $place = Place::findOrFail($id);
        return view('place.view', compact('place'));
    } 

    /**
     * Create place page (disabled)
     */
    public function create()
    {
        return view('place.create');
    } 

    /**
     * Store new place (disabled)
     * @test test_it_store_new_place_with_error_required_fields
     */
    public function store(StorePlaceRequest $request)
    {
        if($request->validated()) {
            $place = new Place;
            $place->city = $request->city;
            $place->country = $request->country;
            $place->date = \Carbon\Carbon::createFromFormat('Y-m-d',$request->date)->format('Y-m-d'); // format date
            if($place->save()) {
                return redirect()->route('place.index')->with('success', 'Place is created!');
            }
        }
    } 

    /**
     * Export place using RapidAPI to store data for hotels and weather
     * @test test_it_user_can_submit_export_form_with_error_required_fields
     * @test test_it_user_can_submit_export_form
     */
    public function export(ExportPlaceRequest $request)
    {
        $data = $request->validated();
        $country = $data['country'];
        $city = $data['city'];
        $date = $data['date'];
        $full_name = $city.', '.$country;
        // Date needs to be formated
        $formated_date = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        $clientHotels = HotelClient::searchByGroup($full_name, null, 'CITY_GROUP', $limit = 1);
        $destination_id = null;
        $geo_id = null;
        if(!empty($clientHotels)) {
            $destination_id = intval($clientHotels[0]['destinationId']);
            $geo_id = intval($clientHotels[0]['geoId']);
        }
        $place = Place::firstOrCreate([
            'api_destination_id' => $destination_id,
            'api_geo_id' => $geo_id,
            'country' => $country,
            'city' => $city,
            'date' => $formated_date,
        ]);

        // Store new place with destination id
        if($place->save()) {
            return redirect()->route('place.hotel.export', $place->id);
        }
    }

    /**
     * Search of hotel that are recomended by RapidAPI
     * @test test_it_user_can_see_page_export_hotels
     */
    public function exportHotel($id)
    {
        $place = Place::findOrFail($id);
        $clientHotels = HotelClient::searchByGroup($place->api_destination_id, $place->date->format('Y-m-d'), 'HOTEL_GROUP', $limit = 3);
        $clientWeather = WeatherClient::currentWeather($place->city);
        return view('place.export_hotel', compact('place', 'clientHotels', 'clientWeather'));
    } 

    /**
     * Store hotels that are recommeded by RapidAPI
     * @test test_it_user_can_submit_place_export_hotels
     */
    public function exportHotelStore(Request $request, $id)
    {
        $place = Place::findOrFail($id);
        $reportMessage = '';
        // Store weather if is checked export_weather_check
        if(isset($request->export_weather_check)) {
            $client_weather = json_decode($request->client_weather);
            if(!empty($client_weather->list)) {
                foreach($client_weather->list as $list) {
                    // First check if wather for current date exists
                    if(!$place->weathers()->todayWeather($list->dt)->exists()) {
                        //Save new Weather
                        $new_weather = new Weather;
                        $new_weather->api_weather_id = $list->weather[0]->id;
                        $new_weather->main = $list->weather[0]->main;
                        $new_weather->description = $list->weather[0]->description;
                        $new_weather->icon = $list->weather[0]->icon;
                        $new_weather->temp_day = $list->temp->day;
                        $new_weather->temp_min = $list->temp->min;
                        $new_weather->temp_max = $list->temp->max;
                        $new_weather->temp_night = $list->temp->night;
                        $new_weather->temp_eve = $list->temp->eve;
                        $new_weather->temp_morn = $list->temp->morn;
                        $new_weather->date = \Carbon\Carbon::createFromTimestamp($list->dt)->format('Y-m-d H:i:s');
                        $place->weathers()->save($new_weather);
                    } else {
                        $reportMessage .= 'Weather for date '.(\Carbon\Carbon::createFromTimestamp($list->dt)->format('Y-m-d')).' already exists ';
                    }
                }
            }
        }
        // Store suggested hotels
        if(!empty($request->client_hotels)) {
            foreach($request->client_hotels as $client_hotel) {
                $client_hotel = json_decode($client_hotel);
                // Check if hotel exists
                if(!$place->hotels()->haveHotel($client_hotel->name)->exists()) {
                    // Save new hotel
                    $new_hotel = new Hotel;
                    $new_hotel->api_hotel_id = $client_hotel->api_hotel_id;
                    $new_hotel->name = $client_hotel->name;
                    $new_hotel->price = $client_hotel->price;
                    $new_hotel->guest_review_txt = $client_hotel->guest_review_txt;
                    $new_hotel->guest_review_num = $client_hotel->guest_review_num;
                    $new_hotel->star_rating = $client_hotel->star_rating;
                    $new_hotel->lat = $client_hotel->lat;
                    $new_hotel->long = $client_hotel->long;
                    $new_hotel->street_address = $client_hotel->street_address;
                    $new_hotel->extended_address = $client_hotel->extended_address;
                    $new_hotel->locality = $client_hotel->locality;
                    $new_hotel->postal_code = $client_hotel->postal_code;
                    $new_hotel->region = $client_hotel->region;
                    $new_hotel->country_name = $client_hotel->country_name;
                    $new_hotel->country_code = $client_hotel->country_code;
                    $new_hotel->thumbnail_url = $client_hotel->thumbnail_url;
                    // Save new hotel assign to current place
                    $place->hotels()->save($new_hotel);
                } else {
                    $reportMessage .= 'Hotel('.$name.') already exists.';
                }
            }
        }
        
        $message = 'Hotels are submitted! '.$reportMessage;
        if(isset($request->export_weather_check)) {
            $message = 'Hotels and weekly weather are submitted! '.$reportMessage;
        }
        return redirect()->route('place.view', $place->id)->with('success', $message);
    }

    /**
     * Place edit page (disabled)
     * @test
     */
    public function edit($id)
    {
        $place = Place::findOrFail($id);

        return view('place.edit', compact('place'));
    } 

    /**
     * Delete place in /place table list
     * @test test_it_user_can_delete_single_place
     */
    public function delete($id)
    {
        $place = Place::findOrFail($id);

        if($place->delete()) {
            return redirect()->back()->with('success', 'Place deleted!');
        }
    } 

    /**
     * Update place form request (disabled)
     * @test
     */
    public function update(UpdatePlaceRequest $request, $id)
    {
        $place = Place::find($id);
        $place->city = $request->city;
        $place->country = $request->country;
        $place->date = $request->date;

        if($place->save()) {
            return redirect()->back()->with('success', 'Place updated!');
        } else {
            return redirect()->back()->with('error', 'Place cannot be saved!');
        }
    }
}
