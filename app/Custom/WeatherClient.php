<?php

namespace App\Custom;

use App\Custom\RapidApiClient;

/**
 * API class
 */
class WeatherClient 
{
	public static function currentWeather($value)
    {
        $client = 'weather';
        $host = env('RAPIDAPI_WEATHER_HOST', 'x-rapidapi-host: community-open-weather-map.p.rapidapi.com');
        $key = env('RAPIDAPI_KEY', 'x-rapidapi-key: ab7f802e0amshf8692a3e0dd106dp187dacjsnf1566aa8ff65');

        $rapidApiClient = new RapidApiClient($client, $host, $key);
        $result = $rapidApiClient->curlSetup($value);

        if(isset($result['list'])) {
            foreach($result['list'] as $list) {
                $list['weather'][0]['img_src'] = 'http://openweathermap.org/img/wn/'.$list['weather'][0]['icon'].'@2x.png';
            }
        }

        return $result;
    }
}