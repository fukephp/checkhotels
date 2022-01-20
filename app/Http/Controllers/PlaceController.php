<?php

namespace App\Http\Controllers;

use App\Custom\HotelClient;
use App\Custom\WeatherClient;
use App\Http\Requests\ImportCsvRequest;
use App\Http\Requests\SearchPlaceRequest;
use App\Http\Requests\StorePlaceRequest;
use App\Models\Hotel;
use App\Models\Place;
use App\Models\Weather;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    public function index()
    {
        $places = Place::all();
        return view('place.index', compact('places'));
    }

    public function view($id)
    {
        $place = Place::findOrFail($id);
        $clientHotels = HotelClient::searchByGroup($place->city, 'HOTEL_GROUP', $limit = 3);
        return view('place.view', compact('place', 'clientHotels'));
    } 

    public function create()
    {
        return view('place.create');
    } 

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

    public function search(SearchPlaceRequest $request)
    {
        $data = $request->validated();
        $places = Place::where(function($query) use ($data) {
            if(is_null($data['country']) && $data['city'] != '') {
                $query->where('city', $data['city']);
            } else {
                $query->where('country', $data['country'])
                    ->orWhere('city', $data['city'])
                    ->orWhere('date', $data['date']);
            }
            
        })->get();
        // $places = Place::where('city', $data['city'])->where('country', $data['country'])->get();
        return view('place.search', compact('places', 'data'));
    }

    public function exportHotel($id)
    {
        $place = Place::findOrFail($id);
        $clientHotels = HotelClient::searchByGroup($place->city, 'HOTEL_GROUP', $limit = 3);
        $clientWeather = WeatherClient::currentWeather($place->city);
        return view('place.export_hotel', compact('place', 'clientHotels', 'clientWeather'));
    } 

    public function exportHotelStore(Request $request, $id)
    {
        $place = Place::findOrFail($id);
        $reportMessage = '';
        // Store weather if is checked export_weather_check
        if(isset($request->export_weather_check)) {
            $client_weather = json_decode($request->client_weather);
            // dd($client_weather->list[0]);
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
        // Store suggested hotels
        foreach($request->client_hotels as $client_hotel) {
            $client_hotel = json_decode($client_hotel);
            $name = $client_hotel->name;
            $caption = strip_tags($client_hotel->caption);
            $lat = $client_hotel->latitude;
            $long = $client_hotel->longitude;
            // Check if hotel exists
            if(!$place->hotels()->haveHotel($name)->exists()) {
                // Save new hotel
                $new_hotel = new Hotel;
                $new_hotel->name = $name;
                $new_hotel->caption = $caption;
                $new_hotel->lat = $lat;
                $new_hotel->long = $long;
                // Save new hotel assign to current place
                $place->hotels()->save($new_hotel);
            } else {
                $reportMessage .= 'Hotel('.$name.') already exists.';
            }
        }
        $message = 'Hotels are submitted! '.$reportMessage;
        if(isset($request->export_weather_check)) {
            $message = 'Hotels and weekly weather are submitted! '.$reportMessage;
        }
        return redirect()->route('place.view', $place->id)->with('success', $message);
    }
}
