@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('place.index') }}">Places</a></li>
<li class="breadcrumb-item">Create</li>
@endsection

@section('content')
	<div class="row">
	    <div class="col-lg-12">
	        <div class="bg-light p-5 rounded">
	            <!-- Find places -->
	            <div class="row">
	                <div class="col-lg-12">
	                    <h1>Create new place</h1>
	                    <p>Fill all data</p>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			<form class="mt-3 mb-3" method="post" action="{{ route('place.store') }}">
				@csrf
				<div class="form-group">
					<label for="inputCity">City</label>
					<input type="text" name="city" class="form-control form-control-lg" id="inputCity" aria-describedby="cityHelp">
					{{-- <small id="cityHelp" class="form-text text-muted">We'll never share your email with ssssanyone else.</small> --}}
				</div>
				<div class="form-group">
					<label for="inputCountry">Country</label>
					<input type="text" name="country" class="form-control form-control-lg" id="inputCountry" aria-describedby="countryHelp">
					{{-- <small id="countryHelp" class="form-text text-muted">We'll never share your email with aaaanyone else.</small> --}}
				</div>
				<div class="form-group">
					<label for="inputDate">Date</label>
					<input type="date" name="date" class="form-control form-control-lg" id="inputDate">
				</div>
				<button type="submit" class="btn btn-lg btn-primary">Create</button>
			</form>
		</div>
	</div>
@endsection