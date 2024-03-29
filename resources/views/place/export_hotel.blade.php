@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item">Submit hotels</li>
@endsection

@section('content')
<div class="row">
	<div class="col-lg-8">
		<div class="row">
		    <div class="col-lg-12">
		        <div class="bg-light p-5 rounded">
		            <!-- Find places -->
		            <div class="row">
		                <div class="col-lg-12">
		                    <h1>{{ $place->city.', '.$place->country }}</h1>
		                    <p>Submit and store this hotels in current place.</p>
		                    <p>Default parameters: 1 adult(The number of adults in first room), Limit of 3 random results</p>
		                    <p>Check in date: <b>{{ $place->date->format('Y-m-d') }}</b></p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="row">
			@if(empty($clientHotels))
				<div class="col-lg-12">
					<p class="text-muted mt-3 mb-3">No results.</p>
				</div>
			@endif
			<div class="col-lg-12">
				<div class="card-columns">
					    @foreach($clientHotels as $clientHotel)
					        <div class="card bg-light mt-3 mb-3" style="width: 100%;">
				                <div class="card-body text-center">
				                	<img src="{{ $clientHotel['thumbnail_url'] }}" class="card-img-top mb-3" style="height: 105px;">
									<img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $clientHotel['lat'] }},{{ $clientHotel['long'] }}&markers=color:red%7Clabel:C%7C{{ $clientHotel['lat'] }},{{ $clientHotel['long'] }}&zoom=18&size=300x300&key={{ env('GOOGLE_MAPS_STATIC_API_KEY') }}" class="card-img-top mb-3">
				                    <h5 class="card-title">{{ $clientHotel['name'] }}</h5>
				                    <p class="card-text">
				                    	@include('hotel.partials.star_rating', ['star_rating' => $clientHotel['star_rating']])
				                    </p>
				                    <h6 class="card-subtitle mb-2 text-muted">{{ $clientHotel['price'] != '' ? 'Price: '. $clientHotel['price'] : '' }}</h6>
				                </div>
				            </div>
					    @endforeach
					</div>
				</div>
			</div>
	</div>
	<div class="col-lg-4">
		@include('widgets.weekly_weather_forecasts', compact('clientWeather'))
	</div>
</div>
<form action="{{ route('place.hotel.export.store', $place->id) }}" method="post">
	@csrf
	<div class="custom-control custom-checkbox">
	  <input type="checkbox" name="export_weather_check" value="1" class="custom-control-input" id="exportWeather" checked>
	  <label class="custom-control-label" for="exportWeather">Export also weather data?</label>
	</div>
	<input type="hidden" name="client_weather" value="{{ json_encode($clientWeather) }}">
	@foreach($clientHotels as $clientHotel)
		<input type="hidden" name="client_hotels[]" value="{{ json_encode($clientHotel) }}">
	@endforeach
	<div class="row">
		<div class="col-lg-12">
			<button type="submit" class="btn btn-lg btn-primary mt-3">Submit</button>
		</div>
	</div>
</form>
@endsection
