@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item">Import CSV data</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="bg-light p-5 rounded">
            <!-- Find hotels -->
            <div class="row">
                <div class="col-lg-12">
                    <h1>Import CSV data</h1>
                    <p>Store data for places (columns must be country, city, and date)</p>
                    <form action="{{ route('import.perform') }}" method="post" enctype="multipart/form-data">
                    	@csrf
						<div class="input-group mb-3">
							<div class="custom-file">
								<input type="file" name="file" class="custom-file-input" id="inputGroupFile02">
								<label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
							</div>
						</div>
						<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <hr>
        <div class="bg-light p-5 rounded">
        	<h1>Import list</h1>
        	<p>Store data as places?</p>
        	<p class="text-muted"><strong>Reminder:</strong></p>
        	<ul>
        		<li class="text-muted">After import to database places will also get destination id and geo id from RapidAPI(hotels4).</li>
        		<li class="text-muted">After import csv file will be deleted.</li>
        	</ul>
        	@if($imports->isEmpty())
    			<p>No results.</p>
    		@endif
        	<div class="row">
        		@foreach($imports as $import)
        		<div class="col-lg-6">
        			<div class="card" style="width: 100%;">
						  <div class="card-body">
						    <h5 class="card-title">Result:</h5>
						    {{-- <h6 class="card-subtitle mb-2 text-muted">Card data</h6> --}}
						    	<?php $i=0; ?>
						    	@foreach($import->csvdata as $key => $data)
						    		@php
						    			if($key == 0) {
						    				continue;
						    			}
						    			$i++;
						    		@endphp
						    		<span class="mb-2 text-muted fs-12">{{ $i }}.</span>
						    		<ul class="list-group list-group-flush">
								    	<li class="list-group-item"><b>Country:</b> {!! $data[0] !!}</li>
								    	<li class="list-group-item"><b>City:</b> {{ $data[1] }}</li>
								    	<li class="list-group-item"><b>Date:</b> {!! $data[2] !!}</li>
							    	</ul>
							    @endforeach
						  </div>
						  <div class="card-footer">
						  	<div class="row">
			    				<div class="col-lg-12">
			    					<a href="{{ route('import.store.places', $import->id) }}" onclick="return confirm('Are you sure to store this data into places?')" class="btn btn-small btn-primary">Import</a>
			    					<a href="{{ route('import.delete', $import->id) }}" onclick="return confirm('Are you sure to remove this import csv result?')" class="btn btn-small float-right btn-danger">Remove</a>
			    				</div>
			    			</div>
						  </div>
					</div>
        		</div>
        		@endforeach
        	</div>
        </div>
    </div>
</div>
@endsection