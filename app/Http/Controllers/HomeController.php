<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;


class HomeController extends Controller
{
    public function index() 
    {
        $countries = Place::all()->pluck('country')->unique();
        $cities = Place::all()->pluck('city')->unique();
        return view('home.index', compact('cities', 'countries'));
    }
}
