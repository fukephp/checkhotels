<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade as Countries;


class HomeController extends Controller
{
    /**
     * @test test_it_user_can_open_homepage
     * @test test_it_user_can_open_homepage_with_as_loggedin
     * @test test_it_user_can_see_export_form 
     * @return [type] [description]
     */
    public function index() 
    {
        $countries = Countries::getList('en', 'php');
        return view('home.index', compact('countries'));
    }
}
