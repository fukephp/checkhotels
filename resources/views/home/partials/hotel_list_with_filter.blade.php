@php
	if(!isset($search_data)) {
		$search_data = [
			'country' => '',
			'city' => ''
		];
	}
@endphp
<div class="row">
    <div class="col-lg-12">
        <div class="bg-light mt-4 p-5 rounded">
            <h1>Hotels</h1>
            <p class="lead">Find hotels in selected town or city.</p>
            <a href="{{ route('home.index') }}" class="btn btn-outline-primary btn-lg">Export data from RapidAPI</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
		<form action="{{ route('hotel.search') }}" class="form-inline mt-4 mb-3" method="post">
			@csrf
			<label class="sr-only" for="inlineFormInputName2">Country</label>
			<select name="country" class="custom-select mb-2 mr-sm-2" id="country">
                <option value="">Choose country...</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ $search_data['country'] == $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>

			<label class="sr-only" for="inlineFormInputGroupUsername2">City</label>
			<input type="text" placeholder="City" name="city" class="form-control mb-2 mr-sm-2" id="city" placeholder="" value="{{ $search_data['city'] }}">
			<button type="submit" class="btn btn-primary mb-2">Search</button>
			<a href="{{ route('hotel.index') }}" class="btn btn-outline-primary mb-2 ml-2">Reset</a>
		</form>
    </div>
</div>
<div class="row">
	<div class="col-lg-12">
		@if(!$hotels->isEmpty())
		<div class="card-columns">
			@foreach($hotels as $hotel)
		    	<div class="card bg-light" style="">
		            <div class="card-body text-center">
		                <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $hotel->lat }},{{ $hotel->long }}&markers=color:red%7Clabel:C%7C{{ $hotel->lat }},{{ $hotel->long }}&zoom=18&size=300x300&key={{ env('GOOGLE_MAPS_STATIC_API_KEY') }}" class="card-img-top mb-3">
		                <h5 class="card-title">{!! $hotel->name !!}</h5>
		                <h6 class="card-subtitle mb-2 text-muted">{!! $hotel->place->city. ', ' .$hotel->place->country !!}</h6>
		                <p class="card-text">
		                	@include('hotel.partials.star_rating', ['star_rating' => $hotel->star_rating])
		                </p>
		                <p class="card-text text-muted">
		                	{{ $hotel->price }}
		                </p>
		            </div>
		        </div>
		    @endforeach
		</div>
		@else
			<p class="text-muted pb-5">No results.</p>
		@endif
	</div>
</div>