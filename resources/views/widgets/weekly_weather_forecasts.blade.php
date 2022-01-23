@if(!is_null($clientWeather))
@if($clientWeather['cod'] == 200)
	<div class="row">
		<div class="col-lg-12">
			<div class="card mb-3">
				<div class="row no-gutters">
					<div class="col-md-6">
						<!-- Today weather -->
						<div class="p-3 d-flex bg-info flex-column align-items-center justify-content-center" style="height: 100%;">
							<p class="text-white"><strong>{{ $clientWeather['city']['name'] }}</strong></p>
							<p class="text-white"><small>Today</small></p>
							<img src="http://openweathermap.org/img/wn/{{ $clientWeather['list'][0]['weather'][0]['icon'] }}@2x.png" alt="{{ $clientWeather['list'][0]['weather'][0]['main'] }}" class="">
							<h1 class="text-white">{{ intval($clientWeather['list'][0]['temp']['day']) }}&#8451;</h1>
							<p class="text-white">{{ Carbon\Carbon::createFromTimestamp($clientWeather['list'][0]['dt'])->format('l') }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card-body p-0">
							<ul class="list-group list-group-flush">
							@foreach($clientWeather['list'] as $key => $list)
								@php
									if($key == 0)
										continue;
								@endphp
								<li class="list-group-item">
									<img src="http://openweathermap.org/img/wn/{{ $list['weather'][0]['icon'] }}.png" alt="{{ $list['weather'][0]['main'] }}" class="float-left mr-2">
									<p><strong>{{ intval($list['temp']['day']) }}&#8451;</strong></p>
									<p><small>{{ Carbon\Carbon::createFromTimestamp($list['dt'])->format('D') }}</small></p>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
@endif