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
        $response = self::connectClient($value);

        if(isset($response['list'])) {
            foreach($response['list'] as $list) {
                $list['weather'][0]['img_src'] = 'http://openweathermap.org/img/wn/'.$list['weather'][0]['icon'].'@2x.png';
            }
        }

        return $response;
    }

    protected static function connectClient($value) 
    {
        $client = 'weather';
        $host = env('RAPIDAPI_WEATHER_HOST');
        $key = env('RAPIDAPI_KEY');

        $rapidApiClient = new RapidApiClient($client, $host, $key);
        $response = $rapidApiClient->curlSetup($value);

        return $response;
    }
}