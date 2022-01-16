<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCsvRequest;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index()
    {
        return view('place.index');
    }
}
