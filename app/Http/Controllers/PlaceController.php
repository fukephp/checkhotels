<?php

namespace App\Http\Controllers;

use App\Custom\ApiHotelClient;
use App\Http\Requests\ImportCsvRequest;
use App\Http\Requests\SearchPlaceRequest;
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

    public function exportHotelStore(Request $request)
    {

        dd($request->validated());
    } 

    public function exportWeather($id)
    {
        $place = Place::findOrFail($id);
        dd($place);
    }
}
