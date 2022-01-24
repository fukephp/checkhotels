@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('place.index') }}">Places</a></li>
<li class="breadcrumb-item">{{ $place->city }}</li>
@endsection

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
						<div class="card-body text-center">
							<img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $hotel->lat }},{{ $hotel->long }}&markers=color:red%7Clabel:C%7C{{ $hotel->lat }},{{ $hotel->long }}&zoom=18&size=300x300&key={{ env('GOOGLE_MAPS_STATIC_API_KEY') }}" class="card-img-top mb-3">
							<h5 class="card-title">{{ $hotel->name }}</h5>
							{{-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> --}}
							<p class="card-text">
								@include('hotel.partials.star_rating', ['star_rating' => $hotel->star_rating])
							</p>
							<h6 class="card-subtitle mb-2 text-muted">{{ $hotel->price }}</h6>
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