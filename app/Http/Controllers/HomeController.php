<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Hotel;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index() 
    {
        $countries = Place::all()->pluck('country')->unique();
        $cities = Place::all()->pluck('city')->unique();
        return view('home.index', compact('cities', 'countries'));
    }
}
