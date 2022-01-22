@extends('layouts.master')

@section('content')
    @auth
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-light p-5 rounded mb-4">
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
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-light p-5 rounded">
                    <!-- Find hotels -->
                    <div class="row">
                        <div class="col-lg-12">
                            <h1>Export data</h1>
                            <p>Select country and city to find suggested three hotels or check daily weather forecast</p>
                            <form action="{{ route('place.search') }}" class="needs-validation" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="country">Country</label>
                                        <select name="country" class="custom-select d-block w-100" id="country">
                                            <option value="">Choose country...</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <div class="invalid-feedback">
                                            Please select a valid country.
                                        </div> --}}
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="city">City</label>
                                        <input type="text" name="city" class="form-control" id="city" placeholder="" value="{{ old('city') }}">
                                        {{-- <div class="invalid-feedback">
                                            Please provide a valid city.
                                        </div> --}}
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" class="form-control" id="date" placeholder="" value="{{ old('date') }}">
                                        {{-- <div class="invalid-feedback">
                                            Date code required.
                                        </div> --}}
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endauth

        @guest
        <div class="bg-light p-5 rounded">
        <h1>Homepage</h1>
            <p class="lead">Your viewing the home page. Please login to view the restricted data.</p>
        </div>
        @endguest
@endsection