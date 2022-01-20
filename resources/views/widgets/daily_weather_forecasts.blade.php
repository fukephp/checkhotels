@php
	$today_weather = $place->weathers()->todayWeather(time())->first();
@endphp
@if($today_weather)
<div class="card mb-3" style="">
	<div class="row no-gutters">
		<div class="col-md-12">
			<div class="p-3 d-flex bg-info flex-column align-items-center justify-content-center" style="height: 100%;">
				<p class="text-white"><strong>{{ $place->city }}</strong></p>
				<p class="text-white"><small>Today</small></p>
				<img src="http://openweathermap.org/img/wn/{{ $today_weather->icon }}@2x.png" alt="{{ $today_weather->main }}" class="">
				<h1 class="text-white">{{ intval($today_weather->temp_day) }}&#8451;</h1>
				<p class="text-white">{{ $today_weather->date->format('l') }}</p>
			</div>
		</div>
	</div>
</div>
@endif