@extends('layouts.master')

@php
    $result = '';
    foreach($data as $key => $input) {
        $d = $input != null ? $input.' ' : $input;
        $result .= $d;
    }
@endphp

@section('breadcrumb')
<li class="breadcrumb-item">Search by {{ $result }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="bg-light p-5 rounded">
            <!-- Find places -->
            <div class="row">
                <div class="col-lg-12">
                    <h1>Search result </h1>
                    <p>Get current locations of hotels or current weather in selected place. You can store all information about this place.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
@foreach($places as $place)
    <div class="col-lg-4">
        <div class="card bg-light mt-3" style="width: 100%;">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $place->city }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $place->country }}</h6>
            </div>
            <div class="card-footer">
                <a href="{{ route('place.hotel.export', $place->id) }}" class="btn btn-primary btn-sm float-left">Export near hotels</a>
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection