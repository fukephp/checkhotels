<?php

namespace App\Http\Controllers;

use App\Custom\ApiHotelClient;
use App\Http\Requests\ImportCsvRequest;
use App\Http\Requests\SearchPlaceRequest;
use App\Models\Hotel;
use App\Models\Place;
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
        return view('place.view', compact('place'));
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
        $clientHotels = ApiHotelClient::searchHotels($place->city);
        return view('place.export_hotel', compact('place', 'clientHotels'));
    } 

    public function exportHotelStore(Request $request, $id)
    {
        $place = Place::findOrFail($id);
        foreach($request->client_hotels as $client_hotel) {
            $client_hotel = json_decode($client_hotel);
            $name = $client_hotel->name;
            $caption = strip_tags($client_hotel->caption);
            $lat = $client_hotel->latitude;
            $long = $client_hotel->longitude;
            // Save new hotel
            $new_hotel = new Hotel;
            $new_hotel->name = $name;
            $new_hotel->caption = $caption;
            $new_hotel->lat = $lat;
            $new_hotel->long = $long;
            // Save new hotel assign to current place
            $place->hotels()->save($new_hotel);
        }
        //$clientHotels = ApiHotelClient::searchHotels($place->city);
        return redirect()->route('place.view', $place->id)->with('success','Hotels are submitted!');
    } 

    public function exportWeather($id)
    {
        $place = Place::findOrFail($id);
        dd($place);
    }
}
