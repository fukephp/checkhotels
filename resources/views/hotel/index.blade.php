@extends('layouts.master')

@section('breadcrumb')
<li class="breadcrumb-item">Hotels</li>
@endsection

@section('content')



	@include('home.partials.hotel_list_with_filter')

@endsection