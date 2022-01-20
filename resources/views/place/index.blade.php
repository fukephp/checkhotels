@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item">Places</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="bg-light p-5 rounded">
            <!-- Find places -->
            <div class="row">
                <div class="col-lg-12">
                    <h1>Places</h1>
                    <p>Select a place and view list of hotels and current weather</p>
                    <a href="{{ route('place.create') }}" class="btn btn-outline-primary btn-lg">Create new place</a>
                    <span class="mr-3 ml-3">Or</span>
                    <a href="{{ route('import.index') }}" class="btn btn-outline-primary btn-lg">Import CSV data for places</a>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
    	<hr>
    	<table class="table" id="placeTable">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">City</th>
		      <th scope="col">Hotels(count)</th>
		      <th scope="col">Country</th>
		      <th scope="col">Date</th>
		    </tr>
		  </thead>
		  <tbody>
		    @foreach($places as $place)
		    	<tr>
		    		<td><a href="{{ route('place.view', $place->id) }}">{{ $place->city }}</a></td>
		    		<td class="text-center"><a href="">{{ $place->hotels->count() }}</a></td>
		    		<td>{{ $place->country }}</td>
		    		<td>{{ $place->date }}</td>
		    	</tr>
		    @endforeach
		  </tbody>
		</table>
    </div>
</div>
@endsection
@section('custom_js')
	<script type="text/javascript">
		$(document).ready( function () {
		    $('#placeTable').DataTable();
		} );
	</script>
@endsection