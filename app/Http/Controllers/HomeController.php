<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade as Countries;


class HomeController extends Controller
{
    public function index() 
    {
        // $countries = Place::all()->pluck('country')->unique();
        // $cities = Place::all()->pluck('city')->unique();
        $countries = Countries::getList('en', 'php');
        return view('home.index', compact('countries'));
    }
}
