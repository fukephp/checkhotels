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
    <div class="col-lg-8">
		<div class="bg-light p-5 rounded">
			<div class="row">
				<div class="col-lg-12">
					<h3>Hotels</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="bg-light p-5 rounded">
			<div class="row">
				<div class="col-lg-12">
					<h3>Weather</h3>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection