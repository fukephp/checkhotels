<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchHotelRequest;
use App\Models\Hotel;
use App\Models\Place;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class HotelController extends Controller
{
	/**
	 * List all hotels with filter
	 * @test test_it_user_can_see_hotels_page
	 */
	public function index(Request $request)
	{
		$filter_city = '';
		if(isset($request->city)) {
			$filter_city = $request->city;
		}
		$countries = Place::all()->pluck('country')->unique();
        $cities = Place::all()->pluck('city')->unique();
        if($filter_city != '') {
        	$hotels = Hotel::whereHas('place', function($q) use ($filter_city) {
	        	$q->where('city', $filter_city);
	        })->get();
        } else {
        	$hotels = Hotel::all();
        }
        return view('hotel.index', compact('countries', 'cities', 'hotels', 'filter_city'));
	} 

	/**
	 * Find hotels by city or country
	 * @test test_it_user_can_user_filter_form_with_input
	 * @test test_it_user_can_user_filter_form_without_fill_input
	 */
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
        $search_data = $data;

        return view('hotel.index', compact('countries', 'hotels', 'search_data'));
    } 
}
