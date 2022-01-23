<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchHotelRequest;
use App\Models\Hotel;
use App\Models\Place;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class HotelController extends Controller
{
	public function index()
	{
		$countries = Place::all()->pluck('country')->unique();
        $cities = Place::all()->pluck('city')->unique();
        $hotels = Hotel::all();
        return view('hotel.index', compact('countries', 'cities', 'hotels'));
	} 
    public function search(SearchHotelRequest $request)
    {
    	$data = $request->validated();
    	$hotels = Hotel::whereHas('place', function(Builder $q) use ($data){
    		if(is_null($data['city']) && $data['country'] != '') {
    			$q->where('country', $data['country']);
    		} elseif(is_null($data['country']) && $data['city'] != '') {
    			$q->where('city', $data['city']);
    		} elseif($data['country'] != '' && $data['city'] != '') {
    			$q->where('country', $data['country'])
    				->where('city', $data['city']);
    		}
    	})->get();
    	$countries = Place::all()->pluck('country')->unique();
        $cities = Place::all()->pluck('city')->unique();
        $search_data = $data;

        return view('hotel.index', compact('cities', 'countries', 'hotels', 'search_data'));
    } 
}
