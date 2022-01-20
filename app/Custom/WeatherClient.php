<?php

namespace App\Custom;

/**
 * API class
 */
class WeatherClient
{
	public static function currentWeather($value)
    {
        $result = self::curlSetup($value);

        if(isset($result['list'])) {
            foreach($result['list'] as $list) {
                $list['weather'][0]['img_src'] = 'http://openweathermap.org/img/wn/'.$list['weather'][0]['icon'].'@2x.png';
            }
        }

        return $result;
    }

    /**
     * http://openweathermap.org/img/wn/10d@2x.png
     * Use API from RapidAPI (https://rapidapi.com/community/api/open-weather-map)
     * @param  [type] $value [description]
     * @return [type]       [description]
     */
	protected static function curlSetup($value = null) 
    {
        if(is_null($value)) {
            return false;
        }

        $value = rawurlencode($value);

        $curl = curl_init();
        // Current weather data
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://community-open-weather-map.p.rapidapi.com/forecast/daily?q={$value}&cnt=7&units=metric&mode=json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                env('RAPIDAPI_WEATHER_HOST', 'x-rapidapi-host: community-open-weather-map.p.rapidapi.com'),
                env('RAPIDAPI_KEY', 'x-rapidapi-key: ab7f802e0amshf8692a3e0dd106dp187dacjsnf1566aa8ff65')
            ],
        ]);


        $response = curl_exec($curl);
        $err = curl_error($curl);

        $json_arr = [];

        curl_close($curl);

        if ($err) {
            $json_arr['error'] = "cURL Error #:" . $err;
        } else {
            $json_arr = json_decode($response, TRUE);
        }
        return $json_arr;
    }
}