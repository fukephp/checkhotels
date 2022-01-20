@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('place.index') }}">Places</a></li>
<li class="breadcrumb-item">{{ $place->city }}</li>
@endsection

@php
	// dump($clientHotels);
@endphp

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="bg-light p-5 rounded">
            <!-- Find places -->
            <div class="row">
                <div class="col-lg-12">
                    <h1>{{ $place->city }}</h1>
                    <p>Check list of hotels or current weather.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-9">
		<div class="row">
			@foreach($place->hotels as $hotel)
				<div class="col-lg-4">
					<div class="card mb-3">
						<div class="card-body">
							<h5 class="card-title">{{ $hotel->name }}</h5>
							<img src="https://maps.googleapis.com/maps/api/staticmap?size=300x300&maptype=roadmap\
&markers=size:mid%7Ccolor:red%7C{{ $hotel->name }}&key=AIzaSyAKpeS0-W6Sn4ie_XYoDXtPkQklnnep9RA" class="card-img-top mb-3">
							{{-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> --}}
							<p class="card-text">{{ $hotel->caption }}</p>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="col-lg-3">
		@include('widgets.daily_weather_forecasts', compact('place'))
	</div>
</div>
@endsection