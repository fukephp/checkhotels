@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('place.index') }}">Places</a></li>
<li class="breadcrumb-item">Edit</li>
@endsection

@section('content')
	<div class="row">
	    <div class="col-lg-12">
	        <div class="bg-light p-5 rounded">
	            <!-- Find places -->
	            <div class="row">
	                <div class="col-lg-12">
	                    <h1>Edit: {{ $place->country.', '.$place->city }}</h1>
	                    <p>Fill all data</p>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			<form class="mt-3 mb-3" method="post" action="{{ route('place.update', $place->id) }}">
				@csrf
				<div class="form-group">
					<label for="inputCity">City</label>
					<input type="text" name="city" class="form-control form-control-lg" id="inputCity" aria-describedby="cityHelp" value="{{ $place->city }}">
					{{-- <small id="cityHelp" class="form-text text-muted">We'll never share your email with ssssanyone else.</small> --}}
				</div>
				<div class="form-group">
					<label for="inputCountry">Country</label>
					<input type="text" name="country" class="form-control form-control-lg" id="inputCountry" aria-describedby="countryHelp" value="{{ $place->country }}">
				</div>
				<div class="form-group">
					<label for="inputDate">Date</label>
					<input type="date" name="date" class="form-control form-control-lg" id="inputDate" value="{{ $place->date->format('Y-m-d') }}">
				</div>
				<button type="submit" class="btn btn-lg btn-primary">Update</button>
				<button type="submit" name="delete_place" class="btn btn-lg btn-danger float-right" onclick="return confirm('Are you sure to delete this place?')">Delete</button>
			</form>
		</div>
	</div>
@endsection