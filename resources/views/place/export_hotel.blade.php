@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item">Submit hotels</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="bg-light p-5 rounded">
            <!-- Find places -->
            <div class="row">
                <div class="col-lg-12">
                    <h1>{{ $place->city.', '.$place->country }}</h1>
                    <p>Submit and store this hotels in current place.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @foreach($clientHotels as $clientHotel)
        <div class="col-lg-4">
            <div class="card bg-light mt-3" style="width: 100%;">
                <div class="card-body">
					<img src="https://maps.googleapis.com/maps/api/staticmap?size=300x300&maptype=roadmap\
&markers=size:mid%7Ccolor:red%7C{{ $clientHotel['name'] }}&key=AIzaSyAKpeS0-W6Sn4ie_XYoDXtPkQklnnep9RA" class="card-img-top mb-3">
                    <h5 class="card-title">{{ $clientHotel['name'] }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{!! $clientHotel['caption'] !!}</h6>
                </div>
            </div>
        </div>
    @endforeach
</div>
<form action="{{ route('place.hotel.export.store', $place->id) }}" method="post">
	@csrf
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
